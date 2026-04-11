<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenoms',
        'email',
        'telephone',
        'password',
        'role',
        'actif',
        'deactivated_at',
        'deactivated_by',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'actif' => 'boolean',
            'deactivated_at' => 'datetime',
            'last_login' => 'datetime',
        ];
    }

    // Relations
    public function deactivatedBy()
    {
        return $this->belongsTo(User::class, 'deactivated_by');
    }

    public function biens()
    {
        return $this->hasMany(Bien::class, 'gestionnaire_id');
    }

    public function locataires()
    {
        return $this->hasMany(Locataire::class, 'gestionnaire_id');
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class, 'gestionnaire_id');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'gestionnaire_id');
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'gestionnaire_id');
    }

    // Accesseurs
    public function getNomCompletAttribute()
    {
        return "{$this->nom} {$this->prenoms}";
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeGestionnaires($query)
    {
        return $query->where('role', 'gestionnaire');
    }

    // Méthodes
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGestionnaire()
    {
        return $this->role === 'gestionnaire';
    }
}