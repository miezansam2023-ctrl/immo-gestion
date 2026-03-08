<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bien extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference',
        'type',
        'titre',
        'description',
        'adresse',
        'commune',
        'quartier',
        'ville',
        'nombre_pieces',
        'nombre_chambres',
        'nombre_salles_bain',
        'superficie',
        'etage',
        'meuble',
        'equipements',
        'prix_loyer',
        'prix_caution',
        'charges',
        'nom_proprietaire',
        'telephone_proprietaire',
        'email_proprietaire',
        'statut',
        'photos',
        'documents',
        'gestionnaire_id',
        'date_acquisition',
        'notes',
    ];

    protected $casts = [
        'equipements' => 'array',
        'photos' => 'array',
        'documents' => 'array',
        'meuble' => 'boolean',
        'prix_loyer' => 'decimal:2',
        'prix_caution' => 'decimal:2',
        'charges' => 'decimal:2',
        'superficie' => 'decimal:2',
        'date_acquisition' => 'date',
        'photos' => 'array',
        'equipements' => 'array',
        'documents' => 'array',
    ];

    // Relations
    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    public function contratActif()
    {
        return $this->hasOne(Contrat::class)->where('statut', 'actif');
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    // Accesseurs
    public function getAdresseCompleteAttribute()
    {
        return "{$this->adresse}, {$this->quartier}, {$this->commune}, {$this->ville}";
    }

    public function getPrixLoyerFormatAttribute()
    {
        return number_format($this->prix_loyer, 0, ',', ' ') . ' FCFA';
    }

    public function getPrixCautionFormatAttribute()
    {
        return number_format($this->prix_caution, 0, ',', ' ') . ' FCFA';
    }

    // Scopes
    public function scopeDisponibles($query)
    {
        return $query->where('statut', 'disponible');
    }

    public function scopeOccupes($query)
    {
        return $query->where('statut', 'occupe');
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeParCommune($query, $commune)
    {
        return $query->where('commune', $commune);
    }

    public function scopeParVille($query, $ville)
    {
        return $query->where('ville', $ville);
    }

    public function scopeRecherche($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('reference', 'like', "%{$search}%")
                ->orWhere('titre', 'like', "%{$search}%")
                ->orWhere('adresse', 'like', "%{$search}%")
                ->orWhere('commune', 'like', "%{$search}%")
                ->orWhere('quartier', 'like', "%{$search}%");
        });
    }

    // Méthodes
    public function estDisponible()
    {
        return $this->statut === 'disponible';
    }

    public function estOccupe()
    {
        return $this->statut === 'occupe';
    }

    public function genererReference()
    {
        $lastBien = static::orderBy('id', 'desc')->first();
        $number = $lastBien ? $lastBien->id + 1 : 1;
        return 'BIE-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }



    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bien) {
            if (empty($bien->reference)) {
                $bien->reference = $bien->genererReference();
            }
        });
    }
}
