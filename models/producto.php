<?php
	include_once('sqlsrvconnection.php');
	class Product 
  {
		public $code;
		public $product;
		public $description;
		public $price;

		public function __construct()
    {  

    } 

		public function get_products(){ 
      $connection = SqlSrvConnection::getConnectionVendedor();
      $query = 'select mo.id as id, mo.nombre as nombre, ma.nombre as marca, mo.precio as precio, mo.descripcion as descr from catalogo.modelos as mo
                              inner join catalogo.marcas as ma on mo.marca = ma.id';
      $command = sqlsrv_query($connection, $query);
      $found = sqlsrv_has_rows($command);
      $html = '';
      while($product = sqlsrv_fetch_array($command))
      {
        $code = "'".$product['id']."'";
        $html .= '<tr>
                    <td>'.$product['id'].'</td>
                    <td>'.$product['marca'].' '.$product['nombre'].'</td>
                    <td>'.$product['descr'].'</td>
                    <td align="right">'.$product['precio'].'</td>
                    <td align="right">
                      <input type="number" id="'.$product['id'].'" value="1" min="1">
                    </td>
                    <td>
                      <button onClick="addProduct('.$code.');">
                        Agregar
                      </button>
                    </td>
                  </tr>';
      } /*While*/

            sqlsrv_free_stmt($command);
            sqlsrv_close($connection);
      return $html;
   	} 

 		public function search_code($code)
    {
     
      $connection = SqlSrvConnection::getConnection();
      $query = 'select mo.id as id, mo.nombre as nombre, ma.nombre as marca, mo.precio as precio, mo.descripcion as descr from catalogo.modelos as mo
      inner join catalogo.marcas as ma on mo.marca = ma.id WHERE mo.id = ?';
      $params = array($code);
      $command = sqlsrv_query($connection, $query, $params);
      $found = sqlsrv_has_rows($command);
      $html = '';
      $status = 0;
      while($product = sqlsrv_fetch_array($command))
      {
        $this->code = $product['id'];
        $this->product = $product['marca'].' '.$product['nombre'];
        $this->description = $product['descr'];
        $this->price = $product['precio'];
        $status++;
      } /*While*/
      sqlsrv_free_stmt($command);
      sqlsrv_close($connection);
    	return $status;
 		}
	}
?>