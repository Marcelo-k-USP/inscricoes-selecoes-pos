<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class LocalUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'codpes',
    ];

    // uso no crud generico
    protected const fields = [
        [
            'name' => 'name',
            'label' => 'Nome',
        ],
        [
            'name' => 'telefone',
            'label' => 'Celular',
        ],
        [
            'name' => 'email',
            'label' => 'E-mail',
        ],
        [
            'name' => 'password',
            'label' => 'Senha',
            'type' => 'password',
        ],
    ];

    public static function getFields()
    {
        $fields = self::fields;
        return $fields;
    }

    public static function create(string $nome, string $celular, string $email, string $senha)
    {
        $user = new User;
        $user->name = $nome;
        $user->telefone = $celular;
        $user->email = $email;
        $user->password = Hash::make($senha);
        $user->local = '1';
        $user->save();

        return $user;
    }
}
