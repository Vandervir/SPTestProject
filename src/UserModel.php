<?php
namespace TestProject;

use Exception;

/**
 * Class UserModel
 *
 * @method int getId()
 * @method string getUsername()
 * @method string getName()
 * @method string getSurname()
 * @method string getEmail()
 * @method string|null getPhone()
 */
class UserModel
{
    /**
     * @var array
     */
    private $data;

    const FILE_PATH = '../data/user/user_%d.json';

    /**
     * Retrieve user mock
     *
     * @param int $id User Id
     *
     * @throws Exception
     *
     * @return UserModel
     */
    public function loadById(int $id) : self
    {
        $userPath = $this->getPath($id);
        if (!file_exists($userPath)) {
            $this->data = [];

            throw new Exception('User not found');
        }
        $content = file_get_contents($userPath);

        $this->data = json_decode($content);

        return $this;
    }

    /**
     * Getter for
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        if (!isset($this->getData()->{$name})) {
            return null;
        }

        return $this->getData()->{$name};
    }

    /**
     * @param int $id
     *
     * @return string
     */
    public function getPath(int $id): string
    {
        return sprintf(self::FILE_PATH, $id);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
