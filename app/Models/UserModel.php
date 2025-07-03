<?php
namespace Codez\DistrictLounge\Models;

class UserModel extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        $users = $this->dao->select('users', 'user_email = ?', [$email]);
        if (count($users) === 1) {
            return $users[0];
        }
        return null;
    }
    
    public function create(array $data): bool
    {
        // Assure-toi que le mot de passe est haché avant de l'insérer
        if (isset($data['user_password'])) {
            $data['user_password'] = password_hash($data['user_password'], PASSWORD_BCRYPT);
        }
        return $this->dao->insert('users', $data);
    }
    
    public function update(int $id, array $data): bool
    {
        // Assure-toi que le mot de passe est haché avant de l'insérer
        if (isset($data['user_password'])) {
            $data['user_password'] = password_hash($data['user_password'], PASSWORD_BCRYPT);
        }
        return $this->dao->update('users', $data, 'users_id = ?', [$id]);
    }
    
    public function delete(int $id): bool
    {
        return $this->dao->delete('users', 'users_id = ?', [$id]);
    }
    
    public function getAllUsers(): array
    {
        return $this->dao->select('users', '', [], 'users_id ASC');
    }
    
    public function getUserById(int $id): ?array
    {
        $users = $this->dao->select('users', 'users_id = ?', [$id]);
        if (count($users) === 1) {
            return $users[0];
        }
        return null;
    }
}