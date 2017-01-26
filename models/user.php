<?php

class User
{
    // We define our model properties
    // they are public so that we can access them directly e.g. $user->name

    public $id;
    public $name;
    public $email;
    public $password;
    public $dateOfBirth;

    /**
     * User constructor.
     * @param int $id
     * @param string $name
     * @param string $email
     * @param string $password
     * @param DateTime $dateOfBirth
     */
    public function __construct($id, $name, $email, $password, DateTime $dateOfBirth)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->dateOfBirth = $dateOfBirth;
    }

    public static function all()
    {
        $userModels = [];
        $db = Db::getInstance();
        $req = $db->query('SELECT * FROM users');

        // we create a list of User objects from the database results
        foreach ($req->fetchAll() as $userData) {

            $dateOfBirth = new DateTime($userData['date_of_birth']);

            $userModels[] = new User(
                $userData['id'],
                $userData['name'],
                $userData['email'],
                $userData['password'],
                $dateOfBirth
            );
        }

        return $userModels;
    }

    public static function findByEmail($email)
    {
        $db = Db::getInstance();
        // we make sure $email is a string
        $email = strval($email);
        $req = $db->prepare('SELECT * FROM users WHERE email = :email');
        // the query was prepared, now we replace :email with our actual $email value
        $req->execute(array('email' => $email));

        $userData = $req->fetch();

        $dateOfBirth = new DateTime($userData['date_of_birth']);

        return new User(
            $userData['id'],
            $userData['name'],
            $userData['email'],
            $userData['password'],
            $dateOfBirth
        );
    }
}

?>