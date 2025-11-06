<?php

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/up' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::U5hjSsIgLYIz50nE',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tienda',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tienda' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::RFJDqgtVJrM2VCSc',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'login.form',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'login.procesar',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/registro' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'registro.form',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'registro.guardar',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'logout',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/carrito' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'carrito.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/carrito/agregar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'carrito.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/carrito/vaciar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'carrito.clear',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/perfil' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'perfil.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/perfil/lista-deseos' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'perfil.lista-deseos',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/usuarios' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.usuarios.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.usuarios.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/usuarios/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.usuarios.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/categorias' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categorias.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categorias.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/categorias/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categorias.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/productos' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.productos.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.productos.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/productos/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.productos.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/pedidos' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.pedidos.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/peticiones' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.peticiones.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/promociones' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.promociones.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.promociones.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/promociones/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.promociones.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/carrito/(?|actualizar/([^/]++)(*:38)|eliminar/([^/]++)(*:62))|/perfil/lista\\-deseos/(?|agregar/([^/]++)(*:111)|eliminar/([^/]++)(*:136))|/admin/(?|usuarios/(?|([^/]++)(?|/edit(*:183)|(*:191))|bulk\\-delete(*:212))|categorias/(?|([^/]++)(?|/edit(*:251)|(*:259))|bulk\\-delete(*:280))|p(?|ro(?|ductos/(?|([^/]++)(?|/edit(*:324)|(*:332))|bulk\\-delete(*:353))|mociones/(?|([^/]++)(?|(*:385)|/(?|edit(*:401)|toggle\\-status(*:423))|(*:432))|bulk\\-delete(*:453)))|e(?|didos/(?|([^/]++)(?|(*:487)|/edit(*:500)|(*:508))|bulk\\-delete(*:529))|ticiones/(?|([^/]++)(?|(*:561)|/toggle\\-status(*:584))|bulk\\-(?|status(*:608)|delete(*:622))|([^/]++)/(?|responder(*:652)|completar(*:669))))))|/storage/(.*)(*:695))/?$}sDu',
    ),
    3 => 
    array (
      38 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'carrito.update',
          ),
          1 => 
          array (
            0 => 'detalle',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      62 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'carrito.destroy',
          ),
          1 => 
          array (
            0 => 'detalle',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      111 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'perfil.lista-deseos.agregar',
          ),
          1 => 
          array (
            0 => 'producto',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      136 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'perfil.lista-deseos.eliminar',
          ),
          1 => 
          array (
            0 => 'producto',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      183 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.usuarios.edit',
          ),
          1 => 
          array (
            0 => 'usuario',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      191 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.usuarios.update',
          ),
          1 => 
          array (
            0 => 'usuario',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.usuarios.destroy',
          ),
          1 => 
          array (
            0 => 'usuario',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      212 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.usuarios.bulk-delete',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      251 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categorias.edit',
          ),
          1 => 
          array (
            0 => 'categoria',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      259 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categorias.update',
          ),
          1 => 
          array (
            0 => 'categoria',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categorias.destroy',
          ),
          1 => 
          array (
            0 => 'categoria',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      280 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categorias.bulk-delete',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      324 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.productos.edit',
          ),
          1 => 
          array (
            0 => 'producto',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      332 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.productos.update',
          ),
          1 => 
          array (
            0 => 'producto',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.productos.destroy',
          ),
          1 => 
          array (
            0 => 'producto',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      353 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.productos.bulk-delete',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      385 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.promociones.show',
          ),
          1 => 
          array (
            0 => 'promocione',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      401 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.promociones.edit',
          ),
          1 => 
          array (
            0 => 'promocione',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      423 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.promociones.toggle-status',
          ),
          1 => 
          array (
            0 => 'promocion',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      432 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.promociones.update',
          ),
          1 => 
          array (
            0 => 'promocione',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.promociones.destroy',
          ),
          1 => 
          array (
            0 => 'promocione',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      453 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.promociones.bulk-delete',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      487 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.pedidos.show',
          ),
          1 => 
          array (
            0 => 'pedido',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      500 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.pedidos.edit',
          ),
          1 => 
          array (
            0 => 'pedido',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      508 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.pedidos.update',
          ),
          1 => 
          array (
            0 => 'pedido',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      529 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.pedidos.bulk-delete',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      561 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.peticiones.show',
          ),
          1 => 
          array (
            0 => 'peticion',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.peticiones.destroy',
          ),
          1 => 
          array (
            0 => 'peticion',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      584 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.peticiones.toggle-status',
          ),
          1 => 
          array (
            0 => 'peticion',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      608 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.peticiones.bulk-status',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      622 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.peticiones.bulk-delete',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      652 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.peticiones.responder',
          ),
          1 => 
          array (
            0 => 'peticion',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      669 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.peticiones.completar',
          ),
          1 => 
          array (
            0 => 'peticion',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      695 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'storage.local',
          ),
          1 => 
          array (
            0 => 'path',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'generated::U5hjSsIgLYIz50nE' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'up',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:850:"function () {
                    $exception = null;

                    try {
                        \\Illuminate\\Support\\Facades\\Event::dispatch(new \\Illuminate\\Foundation\\Events\\DiagnosingHealth);
                    } catch (\\Throwable $e) {
                        if (app()->hasDebugModeEnabled()) {
                            throw $e;
                        }

                        report($e);

                        $exception = $e->getMessage();
                    }

                    return response(\\Illuminate\\Support\\Facades\\View::file(\'C:\\\\LaravelProjects\\\\Crochettittos\\\\tiendaOnline1\\\\vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Foundation\\\\Configuration\'.\'/../resources/health-up.blade.php\', [
                        \'exception\' => $exception,
                    ]), status: $exception ? 500 : 200);
                }";s:5:"scope";s:54:"Illuminate\\Foundation\\Configuration\\ApplicationBuilder";s:4:"this";N;s:4:"self";s:32:"00000000000007fd0000000000000000";}}',
        'as' => 'generated::U5hjSsIgLYIz50nE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tienda' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
        ),
        'uses' => 'App\\Http\\Controllers\\TiendaController@index',
        'controller' => 'App\\Http\\Controllers\\TiendaController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'tienda',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RFJDqgtVJrM2VCSc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tienda',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
        ),
        'uses' => 'App\\Http\\Controllers\\TiendaController@index',
        'controller' => 'App\\Http\\Controllers\\TiendaController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::RFJDqgtVJrM2VCSc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'login.form' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
        ),
        'uses' => 'App\\Http\\Controllers\\LoginController@mostrarLogin',
        'controller' => 'App\\Http\\Controllers\\LoginController@mostrarLogin',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'login.form',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'login.procesar' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
        ),
        'uses' => 'App\\Http\\Controllers\\LoginController@procesarLogin',
        'controller' => 'App\\Http\\Controllers\\LoginController@procesarLogin',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'login.procesar',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'registro.form' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'registro',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
        ),
        'uses' => 'App\\Http\\Controllers\\LoginController@mostrarRegistro',
        'controller' => 'App\\Http\\Controllers\\LoginController@mostrarRegistro',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'registro.form',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'registro.guardar' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'registro',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
        ),
        'uses' => 'App\\Http\\Controllers\\LoginController@procesarRegistro',
        'controller' => 'App\\Http\\Controllers\\LoginController@procesarRegistro',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'registro.guardar',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'logout' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
        ),
        'uses' => 'App\\Http\\Controllers\\LoginController@logout',
        'controller' => 'App\\Http\\Controllers\\LoginController@logout',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'logout',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'carrito.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'carrito',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CarritoController@index',
        'controller' => 'App\\Http\\Controllers\\CarritoController@index',
        'as' => 'carrito.index',
        'namespace' => NULL,
        'prefix' => '/carrito',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'carrito.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'carrito/agregar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CarritoController@store',
        'controller' => 'App\\Http\\Controllers\\CarritoController@store',
        'as' => 'carrito.store',
        'namespace' => NULL,
        'prefix' => '/carrito',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'carrito.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'carrito/actualizar/{detalle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CarritoController@update',
        'controller' => 'App\\Http\\Controllers\\CarritoController@update',
        'as' => 'carrito.update',
        'namespace' => NULL,
        'prefix' => '/carrito',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'carrito.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'carrito/eliminar/{detalle}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CarritoController@destroy',
        'controller' => 'App\\Http\\Controllers\\CarritoController@destroy',
        'as' => 'carrito.destroy',
        'namespace' => NULL,
        'prefix' => '/carrito',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'carrito.clear' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'carrito/vaciar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CarritoController@clear',
        'controller' => 'App\\Http\\Controllers\\CarritoController@clear',
        'as' => 'carrito.clear',
        'namespace' => NULL,
        'prefix' => '/carrito',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'perfil.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'perfil',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PerfilController@index',
        'controller' => 'App\\Http\\Controllers\\PerfilController@index',
        'as' => 'perfil.index',
        'namespace' => NULL,
        'prefix' => '/perfil',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'perfil.lista-deseos' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'perfil/lista-deseos',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PerfilController@listaDeseos',
        'controller' => 'App\\Http\\Controllers\\PerfilController@listaDeseos',
        'as' => 'perfil.lista-deseos',
        'namespace' => NULL,
        'prefix' => '/perfil',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'perfil.lista-deseos.agregar' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'perfil/lista-deseos/agregar/{producto}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PerfilController@agregarListaDeseos',
        'controller' => 'App\\Http\\Controllers\\PerfilController@agregarListaDeseos',
        'as' => 'perfil.lista-deseos.agregar',
        'namespace' => NULL,
        'prefix' => '/perfil',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'perfil.lista-deseos.eliminar' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'perfil/lista-deseos/eliminar/{producto}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PerfilController@eliminarListaDeseos',
        'controller' => 'App\\Http\\Controllers\\PerfilController@eliminarListaDeseos',
        'as' => 'perfil.lista-deseos.eliminar',
        'namespace' => NULL,
        'prefix' => '/perfil',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'uses' => 'App\\Http\\Controllers\\AdminController@dashboard',
        'controller' => 'App\\Http\\Controllers\\AdminController@dashboard',
        'as' => 'admin.dashboard',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.usuarios.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/usuarios',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.usuarios.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\UsuarioController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\UsuarioController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.usuarios.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/usuarios/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.usuarios.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\UsuarioController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\UsuarioController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.usuarios.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/usuarios',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.usuarios.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\UsuarioController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\UsuarioController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.usuarios.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/usuarios/{usuario}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.usuarios.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\UsuarioController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\UsuarioController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.usuarios.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/usuarios/{usuario}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.usuarios.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\UsuarioController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\UsuarioController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.usuarios.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/usuarios/{usuario}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.usuarios.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\UsuarioController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\UsuarioController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.usuarios.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/usuarios/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\UsuarioController@bulkDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\UsuarioController@bulkDelete',
        'as' => 'admin.usuarios.bulk-delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categorias.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/categorias',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.categorias.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoriaController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoriaController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categorias.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/categorias/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.categorias.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoriaController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoriaController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categorias.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/categorias',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.categorias.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoriaController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoriaController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categorias.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/categorias/{categoria}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.categorias.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoriaController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoriaController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categorias.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/categorias/{categoria}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.categorias.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoriaController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoriaController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categorias.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/categorias/{categoria}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.categorias.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoriaController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoriaController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categorias.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/categorias/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoriaController@bulkDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoriaController@bulkDelete',
        'as' => 'admin.categorias.bulk-delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.productos.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/productos',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.productos.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\ProductoController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProductoController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.productos.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/productos/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.productos.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\ProductoController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProductoController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.productos.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/productos',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.productos.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\ProductoController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProductoController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.productos.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/productos/{producto}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.productos.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\ProductoController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProductoController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.productos.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/productos/{producto}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.productos.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\ProductoController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProductoController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.productos.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/productos/{producto}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.productos.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\ProductoController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProductoController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.productos.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/productos/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ProductoController@bulkDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\ProductoController@bulkDelete',
        'as' => 'admin.productos.bulk-delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.pedidos.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/pedidos',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.pedidos.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\PedidoController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\PedidoController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.pedidos.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/pedidos/{pedido}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.pedidos.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\PedidoController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\PedidoController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.pedidos.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/pedidos/{pedido}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.pedidos.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\PedidoController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\PedidoController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.pedidos.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/pedidos/{pedido}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.pedidos.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\PedidoController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\PedidoController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.pedidos.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/pedidos/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PedidoController@bulkDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\PedidoController@bulkDelete',
        'as' => 'admin.pedidos.bulk-delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.peticiones.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/peticiones',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.peticiones.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\PeticionController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\PeticionController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.peticiones.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/peticiones/{peticion}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.peticiones.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\PeticionController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\PeticionController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.peticiones.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/peticiones/{peticion}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.peticiones.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\PeticionController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\PeticionController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.peticiones.toggle-status' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/peticiones/{peticion}/toggle-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PeticionController@toggleStatus',
        'controller' => 'App\\Http\\Controllers\\Admin\\PeticionController@toggleStatus',
        'as' => 'admin.peticiones.toggle-status',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.peticiones.bulk-status' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/peticiones/bulk-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PeticionController@bulkStatus',
        'controller' => 'App\\Http\\Controllers\\Admin\\PeticionController@bulkStatus',
        'as' => 'admin.peticiones.bulk-status',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.peticiones.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/peticiones/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PeticionController@bulkDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\PeticionController@bulkDelete',
        'as' => 'admin.peticiones.bulk-delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.peticiones.responder' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/peticiones/{peticion}/responder',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PeticionController@responder',
        'controller' => 'App\\Http\\Controllers\\Admin\\PeticionController@responder',
        'as' => 'admin.peticiones.responder',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.peticiones.completar' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/peticiones/{peticion}/completar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PeticionController@completar',
        'controller' => 'App\\Http\\Controllers\\Admin\\PeticionController@completar',
        'as' => 'admin.peticiones.completar',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.promociones.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/promociones',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.promociones.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\PromocionController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\PromocionController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.promociones.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/promociones/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.promociones.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\PromocionController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\PromocionController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.promociones.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/promociones',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.promociones.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\PromocionController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\PromocionController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.promociones.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/promociones/{promocione}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.promociones.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\PromocionController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\PromocionController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.promociones.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/promociones/{promocione}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.promociones.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\PromocionController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\PromocionController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.promociones.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/promociones/{promocione}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.promociones.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\PromocionController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\PromocionController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.promociones.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/promociones/{promocione}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'as' => 'admin.promociones.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\PromocionController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\PromocionController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.promociones.toggle-status' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/promociones/{promocion}/toggle-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PromocionController@toggleStatus',
        'controller' => 'App\\Http\\Controllers\\Admin\\PromocionController@toggleStatus',
        'as' => 'admin.promociones.toggle-status',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.promociones.bulk-delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/promociones/bulk-delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'track-user-activity',
          3 => 'auth',
          4 => 'admin',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\PromocionController@bulkDelete',
        'controller' => 'App\\Http\\Controllers\\Admin\\PromocionController@bulkDelete',
        'as' => 'admin.promociones.bulk-delete',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'storage.local' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'storage/{path}',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:3:{s:4:"disk";s:5:"local";s:6:"config";a:5:{s:6:"driver";s:5:"local";s:4:"root";s:66:"C:\\LaravelProjects\\Crochettittos\\tiendaOnline1\\storage\\app/private";s:5:"serve";b:1;s:5:"throw";b:0;s:6:"report";b:0;}s:12:"isProduction";b:0;}s:8:"function";s:323:"function (\\Illuminate\\Http\\Request $request, string $path) use ($disk, $config, $isProduction) {
                    return (new \\Illuminate\\Filesystem\\ServeFile(
                        $disk,
                        $config,
                        $isProduction
                    ))($request, $path);
                }";s:5:"scope";s:47:"Illuminate\\Filesystem\\FilesystemServiceProvider";s:4:"this";N;s:4:"self";s:32:"00000000000008000000000000000000";}}',
        'as' => 'storage.local',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'path' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
