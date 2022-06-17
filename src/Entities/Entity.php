<?php

namespace PhpStudy\Src\Entities;

use Exception;
use PhpStudy\Src\Exceptions\JsonException;

class Entity
{
    public mixed $db;

    public function __construct()
    {
        $this->db = $this->getDb();
    }

    /**
     * Retorna container atual instanciado.
     *
     * @return mixed
     */
    private function getContainer(): mixed
    {
        return $GLOBALS['app']->getContainer();
    }

    /**
     * Retorna instancia de banco.
     *
     * @return mixed
     */
    private function getDb(): mixed
    {
        return $this->getContainer()->get(\PDO::class);
    }

    /**
     * Faz o prepare da query para evitar SQL Injection
     *
     * @param string $query
     * @param array $params
     * @return mixed
     */
    public function query(
        string $query,
        array  $params
    ): mixed
    {
        $stmt = $this->db->prepare("$query");

        for ($i = 1; $i <= count($params); $i++) {
            $stmt->bindValue($i, $params[$i - 1]);
        }

        $stmt->execute();

        return $stmt;
    }

    /**
     * Retorna 1 item por parametros do banco de dados
     *
     * @param array $data
     * @return mixed
     * @throws JsonException
     */
    public function findOneBy(array $data): mixed
    {
        if (count($data) == 0) {
            throw new JsonException('Adicione ao menos um atributo em findOneBy');
        }

        $query = "SELECT * FROM {$this->table} WHERE";
        $params = [];

        foreach ($data as $field => $value) {
            $query .= " {$field} = ?";
            $params[] = $value;

            if (count($data) > 1 && array_key_last($data) != $field) {
                $query .= " AND";
            }

            if (array_key_last($data) == $field) {
                $query .= " ORDER BY id DESC";
            }
        }

        $stmt = $this->query($query, $params);

        return $stmt->fetch();
    }

    /**
     * Atualiza um item do banco de dados
     *
     * @param array $data
     * @param array $where
     * @return mixed
     * @throws JsonException
     */
    public function update(
        array $data,
        array $where
    ): mixed
    {
        if (count($data) == 0) {
            throw new JsonException('Adicione ao menos um data em update');
        }

        if (count($where) == 0) {
            throw new JsonException('Adicione ao menos um where em update');
        }

        $query = "UPDATE {$this->table} SET ";

        $params = [];
        foreach ($data as $field => $value) {
            $query .= " $field = ?";
            $params[] = $value;
        }

        $query .= " WHERE";

        foreach ($where as $field => $value) {
            $query .= " $field = ?";

            if (count($where) > 1 && array_key_last($where) != $field) {
                $query .= " AND";
            }
            $params[] = $value;
        }

        $stmt = $this->query($query, $params);

        return $stmt->fetch();
    }

    /**
     * Salva um item no banco de dados
     *
     * @param array $data
     * @return mixed
     * @throws JsonException
     */
    public function save(array $data): mixed
    {
        if (count($data) == 0) {
            throw new JsonException('Adicione ao menos um data em save');
        }

        if (in_array('id', $data)
            || in_array('created_at', $data)
            || in_array('updated_at', $data)) {
            throw new JsonException('Os campos id,created_at e updated_at em save serão preenchidos automaticamente. Não é necessário passá-los');
        }

        $stmt = $this->db->prepare("DESC {$this->table}");
        $stmt->execute();
        $fields = $stmt->fetchAll();

        $insertFields = "(";
        $valuesField = "(";
        $params = [];

        foreach ($fields as $field) {
            if ($field['Field'] != 'id' && $field['Field'] != 'created_at' && $field['Field'] != 'updated_at') {
                if (array_key_exists($field['Field'], $data)) {
                    $insertFields .= $field['Field'] . ",";
                    $valuesField .= "?,";
                    $params[] = $data[$field['Field']];
                }
            }
        }

        $insertFields = rtrim($insertFields, ",");
        $insertFields .= ")";
        $valuesField = rtrim($valuesField, ",");
        $valuesField .= ")";

        $query = "INSERT INTO {$this->table} {$insertFields} VALUES {$valuesField}";

        $this->query($query, $params);

        return $this->db->lastInsertId();
    }

    /**
     * Deleta um item do banco de dados
     *
     * @param array $where
     * @return mixed
     * @throws JsonException
     */
    public function delete(array $where): mixed
    {
        if (count($where) == 0) {
            throw new JsonException('Adicione ao menos um atributo em delete');
        }

        $query = "DELETE FROM {$this->table} WHERE";
        $params = [];

        foreach ($where as $field => $value) {
            $query .= " {$field} = ?";
            $params[] = $value;

            if (count($where) > 1 && array_key_last($where) != $field) {
                $query .= " AND";
            }
        }

        $stmt = $this->query($query, $params);

        return $stmt->fetch();
    }
}