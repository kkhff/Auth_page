<?php

    class UserModel {
        private $conn;

        public function __construct($db_connection) {
            $this->conn = $db_connection;
        }

        public function createUser($data) {
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sss", $data['username'], $data['email'], $data['password']);
            $success = $stmt->execute();
            if($success){
                $newId = $stmt->insert_id;
                $stmt->close();
                return $newId;
            } else {
                if($stmt->errno === 1062) {
                    $stmt->close();
                    return -1; // Indicate duplicate entry
                }

                error_log("Database error on user creation : " . $stmt->error);
                $stmt->close();
                return 0; // Indicate general failure
            }
        }

        public function getUserByUsernameOrEmail($identifier) {
            $sql = "SELECT * FROM users WHERE  username = ? OR email = ? LIMIT 1"; 
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $identifier, $identifier);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result->fetch_assoc();
        }

        public function verifyPassword($identifier, $password) {
            $user = $this->getUserByUsernameOrEmail($identifier);
            if ($user && password_verify($password, $user['password'])){
                return $user;
            } else {
                return false;
            }
        }

        public function getUserById($userId) {
            $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result->fetch_assoc();
        }


        public function deleteUserById($userId) {
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            return $affectedRows > 0;
        }

        public function isDuplicate($username, $email) {
            $sql = "SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result->num_rows > 0;
        }
    }



?>