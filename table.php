<?php
include 'config.php';
session_start();
if(!isset($_SESSION['username'])) {
    header('location:login.php');die;
}
if(!isset($_SESSION['iddb'])) {
    header('location:index.php');die;
}
$data = json_decode(file_get_contents('db.json'),1);
$db = '';
foreach ($data as $value) {
    if ($value['id'] == $_SESSION['iddb']) {
        $db .= $value['database'];
    }
}
$conn = new mysqli($config['server'].$config['port'],$config['usernameserver'],$config['passwordserver'],$db);
if (isset($_REQUEST['table'])) {
?>

					<table class="table table-striped table-bordered" style="width:100%;">
					        <thead>
					            <tr>
					           	<?php
                                  $sql = $conn->query("DESCRIBE $_REQUEST[table]");
                                  while ($row = $sql->fetch_array(MYSQLI_BOTH)) { ?>
                                    <th><?=$row[0]?></th>
                                <?php } ?>
					            </tr>
					        </thead>
					        <tbody>
					        	<?php
					        		if (isset($_REQUEST['op']) || isset($_REQUEST['field']) || isset($_REQUEST['value'])) {
					        			$query = "SELECT * FROM $_REQUEST[table] WHERE";
					        			foreach ($_REQUEST['field'] as $key => $field) {
					        				$op = $_REQUEST['op'][$key];
					        				$val = $_REQUEST['value'][$key];
					        				$query .= " $op $field='$val'";
					        			}
					        			$sql = $conn->query($query);
					        		}else{
					        			$sql = $conn->query("SELECT * FROM $_REQUEST[table]");
					        		}
									    while ($row = $sql->fetch_array(MYSQLI_BOTH))
									    {
									    ?>
									        <tr>
									        	<?php for ($i=0; $i < $sql->field_count; $i++) { ?>
									        	<td><?=$row[$i]?></td>
									        	<?php }?>
									        </tr>
									   <?php }?>
					        </tbody>
					    </table>
<?php } ?>
