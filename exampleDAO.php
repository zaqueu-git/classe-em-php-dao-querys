<?php
function myCustomErrorHandler(int $errNo, string $errMsg, string $file, int $line) {
    echo "#[$errNo] occurred in [$file] at line [$line]: [$errMsg]";
}

set_error_handler('myCustomErrorHandler');

class ConnectionDriver
{
    private $server;
    private $dbName;
    private $dbUser;
    private $dbPassword;
    private $pdo;

    public function __construct(string $server, string $dbName, string $dbUser, string $dbPassword)
    {
        $this->server = $server;
        $this->dbName = $dbName;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->connect();
    }

    private function connect()
    {
        $dsn = 'mysql:dbname='. $this->dbName .';host=' . $this->server;

        $this->pdo = new PDO($dsn,$this->dbUser,$this->dbPassword);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec("set names utf8");
    }

    public function getPDO()
    {
        return $this->pdo;
    }
}

class ExampleDAO
{
    private $dblocal;

    public function __construct()
    {
        $dblocal = new ConnectionDriver("localhost", "basetest", "root", ""); 
        $this->dblocal = $dblocal->getPDO();
    }

    public function create(string $name, int $age, string $description)
    {
        $sql = $this->dblocal->prepare("INSERT INTO people SET name = :name, age = :age, description = :description");
        $sql->bindParam('name', $name, PDO::PARAM_STR);
        $sql->bindParam('age', $age, PDO::PARAM_INT);
        $sql->bindParam('description', $description, PDO::PARAM_STR);

        if ($sql->execute()) {
            if ($sql->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    public function readAll()
    {
        $sql = $this->dblocal->prepare("SELECT * FROM people");

        if ($sql->execute()) {
            if ($sql->rowCount() > 0) {
                return $sql->fetchAll(PDO::FETCH_OBJ);
            }
        }

        return [];
    }

    public function readById(int $id)
    {
        $sql = $this->dblocal->prepare("SELECT * FROM people WHERE id = :id");
        $sql->bindParam('id', $id, PDO::PARAM_INT);

        if ($sql->execute()) {
            if ($sql->rowCount() > 0) {
                return $sql->fetch(PDO::FETCH_OBJ);
            }
        }

        return false;
    }

    public function readAllWithPage(int $page, int $size)
    {
        if (empty($page)) {
            $page = 1;
        }

        $offset = ($page-1) * $size;

        $sql = $this->dblocal->prepare("SELECT * FROM people LIMIT :offset, :size");
        $sql->bindValue(':offset', $offset, PDO::PARAM_INT);
        $sql->bindValue(':size', $size, PDO::PARAM_INT);

        if ($sql->execute()) {
            if ($sql->rowCount() > 0) {
                return $sql->fetchAll(PDO::FETCH_OBJ);
            }
        }

        return [];
    }

    public function readByIdWithPage(int $id, int $page, int $size)
    {
        if (empty($page)) {
            $page = 1;
        }

        $offset = ($page-1) * $size;

        $sql = $this->dblocal->prepare("SELECT * FROM people WHERE id = :id LIMIT :offset, :size");
        $sql->bindParam('id', $id, PDO::PARAM_INT);
        $sql->bindValue(':offset', $offset, PDO::PARAM_INT);
        $sql->bindValue(':size', $size, PDO::PARAM_INT);

        if ($sql->execute()) {
            if ($sql->rowCount() > 0) {
                return $sql->fetchAll(PDO::FETCH_OBJ);
            }
        }

        return [];
    }
    
    public function updateById(int $id, string $name, int $age, string $description)
    {
        $sql = $this->dblocal->prepare("UPDATE people SET name = :name, age = :age, description = :description WHERE id = :id");
        $sql->bindParam('name', $name, PDO::PARAM_STR);
        $sql->bindParam('age', $age, PDO::PARAM_INT);
        $sql->bindParam('description', $description, PDO::PARAM_STR);
        $sql->bindParam('id', $id, PDO::PARAM_INT);

        if ($sql->execute()) {
            if ($sql->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }
    
    public function deleteById(int $id)
    {
        $sql = $this->dblocal->prepare("DELETE FROM people WHERE id = :id");
        $sql->bindParam('id', $id, PDO::PARAM_INT);

        if ($sql->execute()) {
            if ($sql->rowCount() > 0) {
                return true;
            }
        }

        return false;
    }

    public function totalRecords()
    {
        $sql = $this->dblocal->prepare("SELECT COUNT(*) AS total FROM people");

        if ($sql->execute()) {
            return $sql->fetch(PDO::FETCH_OBJ);
        }

        return 0;
    }

    public function totalRecordsById(int $id)
    {
        $sql = $this->dblocal->prepare("SELECT COUNT(*) AS total FROM people WHERE id = :id");
        $sql->bindParam('id', $id, PDO::PARAM_INT);

        if ($sql->execute()) {
            return $sql->fetch(PDO::FETCH_OBJ);
        }

        return 0;
    }

    public function sumRecords()
    {
        $sql = $this->dblocal->prepare("SELECT SUM(age) AS total FROM people");

        if ($sql->execute()) {
            return $sql->fetch(PDO::FETCH_OBJ);
        }

        return 0;
    }

    public function sumRecordsById(int $id)
    {
        $sql = $this->dblocal->prepare("SELECT SUM(age) AS total FROM people WHERE id = :id");
        $sql->bindParam('id', $id, PDO::PARAM_INT);

        if ($sql->execute()) {
            return $sql->fetch(PDO::FETCH_OBJ);
        }

        return 0;
    }

}

try {

    $ExampleDAO = new ExampleDAO();

    // CREATE

    /*
    $response = $ExampleDAO->create("Zaqueu", 25, "Olá mundo"); 

    if ($response == false) {
        throw new Exception("Could not create");
    }
    */

    // READ

    /*
    $response = $ExampleDAO->readAll();
    $response = $ExampleDAO->readById(11);
    $response = $ExampleDAO->readAllWithPage(1, 10);
    $response = $ExampleDAO->readByIdWithPage(10, 1, 10);

    if ($response == false) {
        throw new Exception("Could not view");
    }
    */

    // UPDATE

    /*
    $response = $ExampleDAO->updateById(13, "Heitor", 30, "Olá universo");  

    if ($response == false) {
        throw new Exception("Could not update");
    }
    */

    // DELETE

    /*
    $response = $ExampleDAO->deleteById(4);

    if ($response == false) {
        throw new Exception("Could not delete");
    }
    */

    // TOTAL

    /*
    $response = $ExampleDAO->totalRecords();
    $response = $ExampleDAO->totalRecordsById(11);

    if ($response == false) {
        throw new Exception("Could not total records");
    }
    */

    // SUM

    /*
    $response = $ExampleDAO->sumRecords();
    $response = $ExampleDAO->sumRecordsById(3);

    if ($response == false) {
        throw new Exception("Could not sum records");
    }
    */

    //var_dump($response);

} catch (\Throwable $th) {
    die($th->getMessage());
}
