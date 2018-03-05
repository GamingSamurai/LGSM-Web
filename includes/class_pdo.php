<?php

/**
 * http://culttt.com/2012/10/01/roll-your-own-pdo-php-class/
 */
 
?>



<?php // config.php

// Define database configuration
define("DB_HOST", "localhost");
define("DB_USER", "username");
define("DB_PASS", "password");
define("DB_NAME", "database");

?>



<?php // database.class.php

class Database
{
    private $host   = DB_HOST;
    private $user   = DB_USER;
    private $pass   = DB_PASS;
    private $dbname = DB_NAME;
    
    private $dbh;
    private $error;
    
    private $stmt;
    
    public function __construct()
    {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );
        //Create a new PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }
        // Catch any errors
        catch(PDOException $e) {
            $this->error = $e->getMessage();
        }
    }
    
    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }
    
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }
    
    public function execute()
    {
        return $this->stmt->execute();
    }
    
    public function resultset()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }
    
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }
    
    /**
     * Transactions allow multiple changes to a database all in one batch.
     */
    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }
     
    public function endTransaction()
    {
        return $this->dbh->commit();
    }
    
    public function cancelTransaction()
    {
        return $this->dbh->rollBack();
    }
    
    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }
}

?>



<?php //tutorial.php

include 'config.php';
include 'database.class.php';

$db = new Database();

/**
 * Insert a new record
 */
$db->query('INSERT INTO mytable (placeholder, placeholder, placeholder, placeholder) VALUES (:fname, :lname, :age, :gender)');

$db->bind(':fname', 'John');
$db->bind(':lname', 'Smith');
$db->bind(':age', '24');
$db->bind(':gender', 'male');

$db->execute();

echo $db->lastInsertId();

/**
 * Insert multiple records using a Transaction
 */
$db->beginTransaction();

$db->query('INSERT INTO mytable (placeholder, placeholder, placeholder, placeholder) VALUES (:fname, :lname, :age, :gender)');

$db->bind(':fname', 'Jenny');
$db->bind(':lname', 'Smith');
$db->bind(':age', '23');
$db->bind(':gender', 'female');

$db->execute();

$db->bind(':fname', 'Jilly');
$db->bind(':lname', 'Smith');
$db->bind(':age', '25');
$db->bind(':gender', 'female');

$db->execute();

echo $db->lastInsertId();

$db->endTransaction();

/**
 * Select a single row
 */
$db->query('SELECT FName, LName, Age, Gender FROM mytable WHERE FName = :fname');

$db->bind(':fname', 'Jenny');

$row = $db->single();

echo "<pre>";
print_r($row);
echo "</pre>";
 
/**
 * Select multiple rows
 */
$db->query('SELECT FName, LName, Age, Gender FROM mytable WHERE LName = :lname');

$db->bind(':lname', 'Smith');

$rows = $db->resultset();

echo "<pre>";
print_r($rows);
echo "</pre>";

echo $db->rowCount();

?>
