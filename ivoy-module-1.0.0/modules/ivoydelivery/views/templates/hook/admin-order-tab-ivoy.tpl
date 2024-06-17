{if !empty($ivoyorder)}
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a>Ivoy Delivery</a></li>
    </ul>
    <div class="tab-content panel">

        <!-- Tab shipping -->
        <div class="tab-pane active" id="shipping">
            <h4 class="visible-print">Transporte <span class="badge">(1)</span></h4>
            <div id="module-info" data-base-url="{$base_url}" data-module-url="{$module_url}"></div>
            <!-- Shipping block -->
            <div class="form-horizontal">
                    <div class="table-responsive">
                        <table class="table" id="ivoy_shipping_table">
                            <thead>
                                <tr>
                                    <th>
                                            <span class="title_box ">Fecha</span>
                                    </th>
                                    <th>
                                            <span class="title_box ">Cliente</span>
                                    </th>
                                    <th>
                                            <span class="title_box ">Distancia</span>
                                    </th>
                                    <th>
                                            <span class="title_box ">Costo</span>
                                    </th>
                                    <th>
                                            <span class="title_box ">Origen</span>
                                    </th>
                                    <th>
                                            <span class="title_box ">Destino</span>
                                    </th>
                                    <th>
                                            <span class="title_box ">Orden generada</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>{$ivoyorder['date_add']}</td>
                                    <td>{$ordercustomer->firstname} {$ordercustomer->lastname} <br> {$ordercustomer->email}</td>
                                    <td>{$ivoyorder['distance']}</td>
                                    <td>{$ivoyorder['total']}</td>
                                    <td>{$ivoyorder['store_name']}</td>
                                    <td>
                                            {$ivoyorder['addr_street']} {$ivoyorder['addr_ext_num']} 
                                            {if !empty($ivoyorder['addr_int_num'])} {$ivoyorder['addr_int_num']} {/if} <br>
                                            {$ivoyorder['addr_neighborhood']} {$ivoyorder['addr_zip_code']}
                                    </td>
                                    <td id="ivoy_shipping_form">
                                        Ivoy Order: {$ivoyorder['id_ivoy_order']}
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                    </div>
            </div>
        </div>
    </div>
{/if}
    
{if !empty($ivoyrequests)}
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a>Ivoy Delivery</a></li>
    </ul>
    <div class="tab-content panel">

        <!-- Tab shipping -->
        <div class="tab-pane active" id="shipping">
            <h4 class="visible-print">Transporte <span class="badge">(1)</span></h4>
            <div id="module-info" data-base-url="{$base_url}" data-module-url="{$module_url}"></div>
            <!-- Shipping block -->
            <div class="form-horizontal">
                    <div class="table-responsive">
                        <table class="table" id="ivoy_shipping_table">
                            <thead>
                                <tr>
                                    <th>
                                            <span class="title_box ">Fecha</span>
                                    </th>
                                    <th>
                                            <span class="title_box ">Cliente</span>
                                    </th>
                                    <th>
                                            <span class="title_box ">Distancia</span>
                                    </th>
                                    <th>
                                            <span class="title_box ">Costo</span>
                                    </th>
                                    <th>
                                            <span class="title_box ">Origen</span>
                                    </th>
                                    <th>
                                            <span class="title_box ">Destino</span>
                                    </th>
                                    <th id="ivoy_shipping_actions">
                                            <span class="title_box ">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>{$ivoyrequests['date_upd']}</td>
                                    <td>{$ordercustomer->firstname} {$ordercustomer->lastname} <br> {$ordercustomer->email}</td>
                                    <td>{$ivoyrequests['distance']}</td>
                                    <td>{$ivoyrequests['total']}</td>
                                    <td>{$ivoyrequests['store_name']}</td>
                                    <td>
                                            {$ivoyrequests['addr_street']} {$ivoyrequests['addr_ext_num']} 
                                            {if !empty($ivoyrequests['addr_int_num'])} {$ivoyrequests['addr_int_num']} {/if} <br>
                                            {$ivoyrequests['addr_neighborhood']} {$ivoyrequests['addr_zip_code']}
                                    </td>
                                    <td id="ivoy_shipping_form">
                                            <form method="post" action="index.php?controller=AdminOrders&amp;token=715cfa60c7c287e7f7ede71d18e836d6&amp;vieworder&amp;id_order=10802">
                                                    <a href="#" id="edit_shipping_number_link" class="edit_shipping_number_link btn btn-default">
                                                        <i class="icon-pencil"></i>
                                                        Editar
                                                    </a>
                                                    <br>
                                                    <a href="#" id="start_ivoy_process" class="btn btn-default" data-id-request='{$ivoyrequests['id_request']}'>
                                                        <i class="icon-pencil"></i>
                                                        Entregar
                                                    </a>
                                            </form>
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                    </div>
            </div>
        </div>
    </div>
{/if}
