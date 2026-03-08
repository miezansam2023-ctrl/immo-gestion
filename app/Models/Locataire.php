<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locataire extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference',
        'civilite',
        'nom',
        'prenoms',
        'date_naissance',
        'lieu_naissance',
        'situation_matrimoniale',
        'telephone',
        'telephone_secondaire',
        'email',
        'adresse_precedente',
        'type_piece',
        'numero_piece',
        'date_delivrance_piece',
        'date_expiration_piece',
        'lieu_delivrance_piece',
        'profession',
        'employeur',
        'adresse_employeur',
        'telephone_employeur',
        'revenus_mensuels',
        'personne_urgence_nom',
        'personne_urgence_telephone',
        'personne_urgence_lien',
        'documents',
        'photo',
        'actif',
        'notes',
        'gestionnaire_id',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_delivrance_piece' => 'date',
        'date_expiration_piece' => 'date',
        'revenus_mensuels' => 'decimal:2',
        'documents' => 'array',
        'actif' => 'boolean',
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
    public function getNomCompletAttribute()
    {
        return "{$this->civilite} {$this->nom} {$this->prenoms}";
    }

    public function getAgeAttribute()
    {
        return $this->date_naissance ? $this->date_naissance->age : null;
    }

    public function getRevenusMensuelsFormatAttribute()
    {
        return number_format($this->revenus_mensuels, 0, ',', ' ') . ' FCFA';
    }

    public function getPieceIdentiteAttribute()
    {
        $types = [
            'cni' => 'Carte Nationale d\'Identité',
            'passeport' => 'Passeport',
            'attestation_identite' => 'Attestation d\'Identité',
        ];
        
        return "{$types[$this->type_piece]} N° {$this->numero_piece}";
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeAvecContrat($query)
    {
        return $query->whereHas('contratActif');
    }

    public function scopeSansContrat($query)
    {
        return $query->whereDoesntHave('contratActif');
    }

    public function scopeRecherche($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('reference', 'like', "%{$search}%")
              ->orWhere('nom', 'like', "%{$search}%")
              ->orWhere('prenoms', 'like', "%{$search}%")
              ->orWhere('telephone', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('numero_piece', 'like', "%{$search}%");
        });
    }

    // Méthodes
    public function aContratActif()
    {
        return $this->contratActif()->exists();
    }

    public function pieceExpiree()
    {
        return $this->date_expiration_piece && $this->date_expiration_piece->isPast();
    }

    public function genererReference()
    {
        $lastLocataire = static::orderBy('id', 'desc')->first();
        $number = $lastLocataire ? $lastLocataire->id + 1 : 1;
        return 'LOC-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($locataire) {
            if (empty($locataire->reference)) {
                $locataire->reference = $locataire->genererReference();
            }
        });
    }
}