<?php
use Phppot\DataSource;

require_once 'DataSource.php';
$db = new DataSource();
$conn = $db->getConnection();



// import schema
if (isset($_POST["import_database"])) {

    if($_FILES["database"]["name"] != '')
    {
        $array = explode(".", $_FILES["database"]["name"]);
        $extension = end($array);

        if($extension == 'sql')
        {
            $connect = mysqli_connect("localhost","root","","task3");
            $output = '';
            $count = 0;
            $file_data = file($_FILES["database"]["tmp_name"]);
            foreach ($file_data as $row) 
            {
                $start_character = substr(trim($row), 0,2);
                if($start_character != '--' || $start_character != '/*' || $start_character != '//' || $row != '')
                {
                    $output = $output . $row;
                    $end_character = substr(trim($row), -1,1);
                    if($end_character == ';')
                    {
                        if(!mysqli_query($connect,$output))
                        {
                            $count++;
                        }
                        $output = '';
                    }
                }
            }
            if($count > 0)
            {
                $type = "error";
                $message = "There is an error in Database Import";
            }
            else
            {
                $type = "success";
                $message = "Database Successfully Imported";
            }

        }
        else
        {
            $type = "error";
            $message = "Invalid File";
        }
    }
    else
    {
        $type = "error";
        $message = "Please Select Sql File";
    }

}


// reset database
if (isset($_POST["reset_database"])) {

    if($_FILES["reset"]["name"] != '')
    {
        $array = explode(".", $_FILES["reset"]["name"]);
        $extension = end($array);

        if($extension == 'sql')
        {
            $connect = mysqli_connect("localhost","root","","task3");
            $output = '';
            $count = 0;
            $file_data = file($_FILES["reset"]["tmp_name"]);
            foreach ($file_data as $row) 
            {
                $start_character = substr(trim($row), 0,2);
                if($start_character != '--' || $start_character != '/*' || $start_character != '//' || $row != '')
                {
                    $output = $output . $row;
                    $end_character = substr(trim($row), -1,1);
                    if($end_character == ';')
                    {
                        if(!mysqli_query($connect,$output))
                        {
                            $count++;
                        }
                        $output = '';
                    }
                }
            }
            if($count > 0)
            {
                $type = "error";
                $message = "There is an error in Database Reset";
            }
            else
            {
                $type = "success";
                $message = "Database Reset Successfully";
            }

        }
        else
        {
            $type = "error";
            $message = "Invalid File";
        }
    }
    else
    {
        $type = "error";
        $message = "Please Select Sql File";
    }

}


// import CSV
if (isset($_POST["import"])) {
    
    $fileName = $_FILES["file"]["tmp_name"];
    
    if ($_FILES["file"]["size"] > 0) {
        
        $file = fopen($fileName, "r");
        $escapeFirstRow = false;
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            $clientName = "";
            if($escapeFirstRow){
                // var_dump($column);

                if (isset($column[0])) {
                    $clientName = mysqli_real_escape_string($conn, explode('@', $column[0])[0]);
                }
                $clientId = "";
                if (isset($column[0])) {
                    $clientId = mysqli_real_escape_string($conn, explode('@', $column[0])[1]);
                }
                $dealName = "";
                if (isset($column[1])) {
                    $dealName = mysqli_real_escape_string($conn, explode('#', $column[1])[0]);
                }
                $dealtId = "";
                if (isset($column[1])) {
                    $dealtId = mysqli_real_escape_string($conn, explode('#', $column[1])[1]);
                }
                $hour = "";
                if (isset($column[2])) {
                    $hour = mysqli_real_escape_string($conn, $column[2]);
                }
                $accepted = "";
                if (isset($column[3])) {
                    $accepted = mysqli_real_escape_string($conn, $column[3]);
                }
                $refused = "";
                if (isset($column[4])) {
                    $refused = mysqli_real_escape_string($conn, $column[4]);
                }
                
                $sqlInsert = "INSERT into users (clientName,clientId,dealName,dealtId,hour,accepted,refused)
                       values (?,?,?,?,?,?,?)";
                $paramType = "sssssss";
                $paramArray = array(
                    $clientName,
                    $clientId,
                    $dealName,
                    $dealtId,
                    $hour,
                    $accepted,
                    $refused
                );
                // var_dump($paramArray);
                // exit();
                $insertId = $db->insert($sqlInsert, $paramType, $paramArray);
                
                if (! empty($insertId)) {
                    $type = "success";
                    $message = "CSV Data Imported into the Database";
                } else {
                    $type = "error";
                    $message = "Problem in Importing CSV Data";
                }
 
            }

            $escapeFirstRow = true;
        }
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <script src="jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">

    <script type="text/javascript" src="index.js"></script>

</head>

<body>
    <h2>Import CSV file into Mysql using PHP</h2>

    <div id="response" class="<?php if(!empty($type)) { echo $type . " display-block"; } ?>">
        <?php if(!empty($message)) { echo $message; } ?>
    </div>

    <div class="outer-scontainer">
        <div class="row">

            <form class="form-horizontal" action="" method="post" name="frmCSVImport" id="frmCSVImport"
                enctype="multipart/form-data">
                <div class="input-row">
                    <label class="col-md-4 control-label">Select Sql File</label>
                    <input type="file" name="database" accept=".sql">
                    <button type="submit" name="import_database" class="btn-submit">Import Database</button>
                </div>
            </form>
        </div>
    </div>

    <br />

    <div class="outer-scontainer">
        <div class="row">

            <form class="form-horizontal" action="" method="post" name="frmCSVImport" id="frmCSVImport"
                enctype="multipart/form-data">
                <div class="input-row">
                    <label class="col-md-4 control-label">Select Sql File</label>
                    <input type="file" name="reset" accept=".sql">
                    <button type="submit" name="reset_database" class="btn-submit">Reset Database</button>
                </div>
            </form>
        </div>
    </div>

    <br />

    <div class="outer-scontainer">
        <div class="row">

            <form class="form-horizontal" action="" method="post" name="frmCSVImport" id="frmCSVImport"
                enctype="multipart/form-data">
                <div class="input-row">
                    <label class="col-md-4 control-label">Choose CSV File</label>
                    <input type="file" name="file" id="file" accept=".csv">
                    <button type="submit" id="submit" name="import" class="btn-submit">Import</button>
                    <br />

                </div>

            </form>

        </div>
        <?php
                        $sqlSelect1 = "DESCRIBE `users`";
                        if($conn->query($sqlSelect1)){
            $sqlSelect = "SELECT * FROM users";
                            
                                        $result = $db->select($sqlSelect);
                                        if (! empty($result)) {

                ?>
        <table id='userTable'>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Client Name</th>
                    <th>ClientId</th>
                    <th>DealName</th>
                    <th>DealtId</th>
                    <th>Hour</th>
                    <th>Accepted</th>
                    <th>Refused</th>

                </tr>
            </thead>
            <?php
                
                foreach ($result as $row) {
                    ?>

            <tbody>
                <tr>
                    <td><?php  echo $row['id']; ?></td>
                    <td><?php  echo $row['clientName']; ?></td>
                    <td><?php  echo $row['clientId']; ?></td>
                    <td><?php  echo $row['dealName']; ?></td>
                    <td><?php  echo $row['dealtId']; ?></td>
                    <td><?php  echo $row['hour']; ?></td>
                    <td><?php  echo $row['accepted']; ?></td>
                    <td><?php  echo $row['refused']; ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <?php }} ?>


    </div>

</body>

</html>