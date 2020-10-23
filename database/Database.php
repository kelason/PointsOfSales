<?php

class Database extends Connection
{
    public function create($table, $fields = []) 
	{
		try {
			$implodeColumns = implode(', ', array_keys($fields));
			$implodePlaceholder = implode(', :', array_keys($fields));

			$sql = "INSERT INTO $table ($implodeColumns) VALUES (:$implodePlaceholder)";
			$stmt = $this->getConnection()->prepare($sql);

			foreach ($fields as $key => $value) {
				$stmt->bindValue(':'.$key,$value);
			}
			$stmt->execute();
			$last_id = $this->getConnection()->lastInsertId();
			
			return $last_id;
		} catch (PDOException $e) {
			throw new Exception($e->getMessage());	
		}
	}

	public function createMany($table, $fields = []) 
	{
		try {
			$this->getConnection()->beginTransaction();
			foreach ($fields as $field) {}

			$implodeColumns = implode(', ', array_keys($field));
			$implodePlaceholder = implode(', :', array_keys($field));

			$sql = "INSERT INTO $table ($implodeColumns) VALUES (:$implodePlaceholder)";
			$stmt = $this->getConnection()->prepare($sql);
			
			foreach ($fields as $field) {
				foreach ($field as $key => $value) {
					$stmt->bindValue(":$key", $value);
				}
				$stmt->execute();
			}
			$this->getConnection()->commit();
			$last_id = $this->getConnection()->lastInsertId();
			
			return $last_id;
		} catch (PDOException $e) {
			$this->getConnection()->rollBack();
			throw new Exception($e->getMessage());	
		}
	}

	public function update($query, $fields = []){
		try {
			$stmt = $this->getConnection()->prepare($query);
			foreach ($fields as $key => $value) {
				$stmt->bindValue(':'.$key,$value);
			}
			$stmtExec = $stmt->execute();
			return $stmtExec;
		} catch (PDOException $e) {
			throw new Exception($e->getMessage());	
		}
	}

	public function destroy($query, $params = []){
		try {
			$stmt = $this->getConnection()->prepare($query);
			return $stmt->execute($params);
		} catch (PDOException $e) {
			throw new Exception($e->getMessage());	
		}
	}

	public function setColumn($query, $params = []){
		try {
			$stmt = $this->getConnection()->prepare($query);
			$stmt->execute($params);
			return $stmt->fetchColumn();	
		} catch (PDOException $e) {
			throw new Exception($e->getMessage());	
		}
	}

	public function setRow($query, $params = []){
		try {
			$stmt = $this->getConnection()->prepare($query);
			$stmt->execute($params);
			return $stmt->fetch();	
		} catch (PDOException $e) {
			throw new Exception($e->getMessage());	
		}
	}

	public function setRows($query, $params = []){
		try {
			$stmt = $this->getConnection()->prepare($query);
			$stmt->execute($params);
			return $stmt->fetchAll();	
		} catch (PDOException $e) {
			throw new Exception($e->getMessage());	
		}
	}

	public function setRowCount($query, $params = []){
		try {
			$stmt = $this->getConnection()->prepare($query);
			$stmt->execute($params);
			return $stmt->rowCount();	
		} catch (PDOException $e) {
			throw new Exception($e->getMessage());	
		}
	}
}