<?php
if(isset($_POST['page'])){
    // Include pagination library file
    include_once 'Pagination.php';

    // Include database configuration file
    require_once 'dbConfig.php';

    // Set some useful configuration
    $baseURL = 'getData.php';
    $offset = !empty($_POST['page'])?$_POST['page']:0;
    $limit = 5;

    // Set conditions for search
    $whereSQL = '';
    if(!empty($_POST['keywords'])){
        $whereSQL = " WHERE (bastidor LIKE '%".$_POST['keywords']."%' OR marca LIKE '%".$_POST['keywords']."%' OR modelo LIKE '%".$_POST['keywords']."%' OR year LIKE '%".$_POST['keywords']."%' OR color LIKE '%".$_POST['keywords']."%') ";
    }
    /*if(isset($_POST['filterBy']) && $_POST['filterBy'] != ''){
        $whereSQL .= (strpos($whereSQL, 'WHERE') !== false)?" AND ":" WHERE ";
        $whereSQL .= " status = ".$_POST['filterBy'];
    }*/

    // Count of all records

    $query   = $db->query("SELECT COUNT(*) as rowNum FROM cars ".$whereSQL);
    $result  = $query->fetch_assoc();
    $rowCount= $result['rowNum'];


    // Initialize pagination class
    $pagConfig = array(
        'baseURL' => $baseURL,
        'totalRows' => $rowCount,
        'perPage' => $limit,
        'currentPage' => $offset,
        'contentDiv' => 'dataContainer',
        'link_func' => 'searchFilter'
    );
    $pagination =  new Pagination($pagConfig);

    // Fetch records based on the offset and limit
    $query = $db->query("SELECT * FROM cars $whereSQL ORDER BY bastidor DESC LIMIT $offset,$limit");
    ?>
    <!-- Data list container -->
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Bastidor</th>
            <th scope="col">Marca</th>
            <th scope="col">Modelo</th>
            <th scope="col">Año</th>
            <th scope="col">Color</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if($query->num_rows > 0){
            while($row = $query->fetch_assoc()){
                $offset++
                ?>
                <tr>
                    <td><?php echo $row["bastidor"]; ?></td>
                    <td><?php echo $row["marca"]; ?></td>
                    <td><?php echo $row["modelo"]; ?></td>
                    <td><?php echo $row["year"]; ?></td>
                    <td><?php echo $row["color"]; ?></td>
                </tr>
                <?php
            }
        }else{
            echo '<tr><td colspan="6">No records found...</td></tr>';
        }
        ?>
        </tbody>
    </table>

    <!-- Display pagination links -->
    <?php echo $pagination->createLinks(); ?>
    <?php
}
?>