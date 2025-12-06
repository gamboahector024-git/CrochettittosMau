document.addEventListener('DOMContentLoaded', () => {
  try {
    const isDashboard = location.pathname.includes('/admin/dashboard');
    const dashboardGrid = document.querySelector('.dashboard-grid');
    if (!isDashboard || !dashboardGrid) return;

    const url = '/admin/dashboard/stats';
    const currencyFmt = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN', maximumFractionDigits: 2 });
    const numberFmt = new Intl.NumberFormat('es-MX');

    function findMetricValueByTitle(title) {
      const cards = document.querySelectorAll('.dashboard-grid .stat-card .card-content');
      for (const card of cards) {
        const h3 = card.querySelector('h3');
        if (h3 && (h3.textContent || '').trim() === title) {
          return card.querySelector('.value');
        }
      }
      return null;
    }

    const $ventasMes = findMetricValueByTitle('Ventas del Mes');
    const $productosVendidos = findMetricValueByTitle('Productos Vendidos');
    const $usuariosActivos = findMetricValueByTitle('Usuarios Activos');
    const $pedidosPendientes = findMetricValueByTitle('Pedidos Pendientes');
    const $visitas = findMetricValueByTitle('Visitas del Sitio');
    const $tasaConversion = findMetricValueByTitle('Tasa de Conversión');
    const $tasaConversionSpan = $tasaConversion ? $tasaConversion.querySelector('span') : null;
    const $peticionesPendientes = findMetricValueByTitle('Peticiones Pendientes');
    const $promocionesActivas = findMetricValueByTitle('Promociones Activas');
    const $lowStockCount = document.querySelector('.low-stock-section .badge.alert-count');
    const $lowStockSection = document.querySelector('.low-stock-section');
    const $lowStockBody = document.querySelector('.low-stock-section .card-body');
    let $lowStockGrid = document.querySelector('.low-stock-section .low-stock-grid');
    let $lowStockEmpty = document.querySelector('.low-stock-section .empty-state');

    async function refreshStats() {
      try {
        const res = await fetch(url, { credentials: 'same-origin' });
        if (!res.ok) return;
        const data = await res.json();

        if ($ventasMes && 'ventasMes' in data) {
          $ventasMes.textContent = currencyFmt.format(Number(data.ventasMes || 0));
        }
        if ($productosVendidos && 'productosVendidos' in data) {
          $productosVendidos.textContent = numberFmt.format(Number(data.productosVendidos || 0));
        }
        if ($usuariosActivos && 'usuariosActivos' in data) {
          $usuariosActivos.textContent = numberFmt.format(Number(data.usuariosActivos || 0));
        }
        if ($pedidosPendientes && 'pedidosPendientes' in data) {
          $pedidosPendientes.textContent = numberFmt.format(Number(data.pedidosPendientes || 0));
        }
        if ($visitas && 'visitas' in data) {
          $visitas.textContent = numberFmt.format(Number(data.visitas || 0));
        }
        if ($peticionesPendientes && 'peticionesPendientes' in data) {
          $peticionesPendientes.textContent = numberFmt.format(Number(data.peticionesPendientes || 0));
        }
        if ($promocionesActivas && 'promocionesActivas' in data) {
          $promocionesActivas.textContent = numberFmt.format(Number(data.promocionesActivas || 0));
        }
        if ($tasaConversion) {
          if (data.tasaConversion === null || typeof data.tasaConversion === 'undefined') {
            if ($tasaConversionSpan) $tasaConversionSpan.textContent = '—'; else $tasaConversion.textContent = '—';
          } else {
            const val = Number(data.tasaConversion);
            if ($tasaConversionSpan) $tasaConversionSpan.textContent = `${val.toFixed(1)}%`; else $tasaConversion.textContent = `${val.toFixed(1)}%`;
          }
        }
        // Actualiza badge y lista de bajo stock (solo visual, sin edición)
        if ($lowStockCount && 'lowStockCount' in data) {
          $lowStockCount.textContent = numberFmt.format(Number(data.lowStockCount || 0));
        }
        if (Array.isArray(data.lowStockProducts)) {
          if (data.lowStockProducts.length > 0) {
            if (!$lowStockGrid && $lowStockBody) {
              $lowStockGrid = document.createElement('div');
              $lowStockGrid.className = 'low-stock-grid';
              $lowStockBody.appendChild($lowStockGrid);
            }
            if ($lowStockEmpty) {
              $lowStockEmpty.remove();
              $lowStockEmpty = null;
            }
            if ($lowStockGrid) {
              $lowStockGrid.innerHTML = data.lowStockProducts.map(p => `
                <div class="low-stock-card ${Number(p.stock) <= 2 ? 'critical' : 'warning'}">
                  <div class="product-info">
                    <div style=\"font-weight: 700; color: var(--text-dark); font-size: 1rem;\">${p.nombre}</div>
                    <small style=\"color: var(--text-muted);\">ID: ${p.id_producto}</small>
                    <div class=\"product-category\">${p.categoria ?? 'Sin categoría'}</div>
                    <div class=\"stock-info\">
                      <span class=\"stock-level\">
                        <i class=\"fas fa-boxes\"></i>
                        Stock: ${p.stock}
                      </span>
                    </div>
                  </div>
                  <div class=\"product-actions\"></div>
                </div>
              `).join('');
            }
          } else {
            if ($lowStockGrid) {
              $lowStockGrid.innerHTML = '';
            }
            if (!$lowStockEmpty && $lowStockBody) {
              $lowStockEmpty = document.createElement('div');
              $lowStockEmpty.className = 'empty-state';
              $lowStockEmpty.innerHTML = `
                <i class=\"fas fa-check-circle success-icon\"></i>
                <h3>¡Excelente!</h3>
                <p>No hay productos con bajo stock en este momento.</p>
              `;
              $lowStockBody.appendChild($lowStockEmpty);
            }
          }
        }
      } catch (e) {
        console.error('Dashboard polling error:', e);
      }
    }

    refreshStats();
    setInterval(refreshStats, 15000);
  } catch (e) {
    console.error('Init dashboard polling error:', e);
  }
});
