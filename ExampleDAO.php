<?php
class ExampleDAO
{
    public function fetchAllCars($campo1)
    {
        $sql = $this->db->prepare("SELECT * FROM tabela WHERE campo1 = :campo1");
        $sql->bindValue(':campo1', $campo1);

        if ($sql->execute()) {
            return $sql->fetchAll(PDO::FETCH_OBJ);
        }
        return [];
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    public function delete($campo1)
    {
        $sql = $this->db->prepare("DELETE FROM tabela WHERE campo1 = :campo1");
        $sql->bindValue(':campo1', $campo1);

        if ($sql->execute()) {
            return 1;
        }
        return 0;
    }

    public function update($campo1, $campo2)
    {
        $sql = $this->db->prepare("UPDATE tabela SET campo2 = :campo2 WHERE campo1 = :campo1");
        $sql->bindValue(':campo1', $campo1);
        $sql->bindValue(':campo2', $campo2);

        if ($sql->execute()) {
            return 1;
        }
        return 0;
    }

    public function getById($campo1)
    {
        $sql = $this->db->prepare("SELECT * FROM tabela WHERE campo1 = :campo1");
        $sql->bindValue(':campo1', $campo1);        

        if ($sql->execute()) {
            return $sql->fetchAll(PDO::FETCH_OBJ);
        }
        return [];
    }

    public function getAll()
    {
        $sql = $this->db->prepare("SELECT * FROM tabela");

        if ($sql->execute()) {
            return $sql->fetchAll(PDO::FETCH_OBJ);
        }
        return [];
    }

    public function insert($campo1, $campo2)
    {
        $sql = $this->db->prepare("INSERT INTO tabela SET campo1 = :campo1, campo2 = :campo2, campo3 = :campo3");
        $sql->bindValue(':campo1', $campo1);
        $sql->bindValue(':campo2', $campo2);
        $sql->bindValue(':campo3', date('Y-m-d H:i:s'));

        if ($sql->execute()) {
            return 1;
        }
        return 0;
    }    
}
?>