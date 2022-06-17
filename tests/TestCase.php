<?php

namespace PhpStudy\Tests;

use PHPUnit\Framework\Constraint\IsEqual;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Psr7\Uri;

class TestCase extends \PHPUnit\Framework\TestCase
{
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->setupDB();
    }

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Inicia transacao
     *
     * @return void
     */
    private function setupDB()
    {
        $app = $GLOBALS['app']->getContainer();
        $this->db = $app->get(\PDO::class);

        if (!$this->db->inTransaction()) {
            $this->db->beginTransaction();
        }
    }

    /**
     * Executa uma chamada em endpoint
     *
     * @param string $requestMethod
     * @param string $requestUri
     * @param array $requestData
     * @param array $headers
     * @return Response
     */
    public function runApp(
        string $requestMethod,
        string $requestUri,
        array  $requestData = [],
        array  $headers = []
    ): Response
    {
        $uri = new Uri('', '', 80, $requestUri);
        $stream = (new StreamFactory())->createStream();

        $headerObject = new Headers();
        foreach ($headers as $name => $value) {
            $headerObject->addHeader($name, $value);
        }

        $requestFactory = new Request($requestMethod, $uri, $headerObject, [], [], $stream);
        $request = $requestFactory->withParsedBody($requestData);

        return $GLOBALS['app']->handle($request);
    }

    /**
     * Instancia de factory para match de campos
     *
     * @param string $factory
     * @return mixed
     */
    public function seed(string $factory): mixed
    {
        require_once '././data/factories/Factory.php';
        require_once '././data/factories/' . $factory . '.php';
        $objectFactory = new $factory;

        $fields = $objectFactory->columns();

        foreach ($fields as $field => $value) {
            $objectFactory->{$field} = $value;
        }

        return $objectFactory;
    }

    /**
     * Verifica se o dado existe no banco
     *
     * @param string $table
     * @param array $data
     * @return void
     */
    public function seeInDatabase(
        string $table,
        array  $data
    ): void
    {
        $query = "SELECT * FROM {$table} WHERE";

        foreach ($data as $field => $value) {
            $query .= " {$field} = $value";

            if (count($data) > 1 && array_key_last($data) != $field) {
                $query .= " AND";
            }

            if (array_key_last($data) == $field) {
                $query .= " ORDER BY id DESC";
            }
        }

        $stmt = $this->db->prepare("$query");
        $stmt->execute();

        $constraint = new IsEqual(count($stmt->fetch()) > 0);

        static::assertThat(true, $constraint);
    }

    /**
     * Verifica se o dado existe no banco
     *
     * @param string $table
     * @param array $data
     * @return void
     */
    public function seeNotInDatabase(
        string $table,
        array  $data
    ): void
    {
        $query = "SELECT * FROM {$table} WHERE";

        foreach ($data as $field => $value) {
            $query .= " {$field} = $value";

            if (count($data) > 1 && array_key_last($data) != $field) {
                $query .= " AND";
            }

            if (array_key_last($data) == $field) {
                $query .= " ORDER BY id DESC";
            }
        }

        $stmt = $this->db->prepare("$query");
        $stmt->execute();

        $constraint = new IsEqual(count(!$stmt->fetch() ? [] : $stmt->fetch()) == 0);

        static::assertThat(true, $constraint);
    }

    /**
     * Rollback da função de salvar no banco
     */
    public function __destruct()
    {
        if ($this->db->inTransaction()) {
            $this->db->rollback();
        }
    }
}