<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contrat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero',
        'bien_id',
        'locataire_id',
        'gestionnaire_id',
        'date_debut',
        'date_fin',
        'duree_mois',
        'date_signature',
        'loyer_mensuel',
        'caution',
        'charges_mensuelles',
        'frais_agence',
        'jour_paiement',
        'mode_paiement',
        'animaux_autorises',
        'sous_location_autorisee',
        'conditions_particulieres',
        'etat_lieux_entree',
        'date_etat_lieux_entree',
        'etat_lieux_sortie',
        'date_etat_lieux_sortie',
        'signature_locataire',
        'signature_proprietaire',
        'signature_gestionnaire',
        'renouvellement_automatique',
        'preavis_jours',
        'statut',
        'date_resiliation',
        'motif_resiliation',
        'fichier_pdf',
        'documents_annexes',
        'notes',
        'date_etat_des_lieux',
        
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_signature' => 'date',
        'date_etat_lieux_entree' => 'date',
        'date_etat_lieux_sortie' => 'date',
        'date_resiliation' => 'date',
        'loyer_mensuel' => 'decimal:2',
        'caution' => 'decimal:2',
        'charges_mensuelles' => 'decimal:2',
        'frais_agence' => 'decimal:2',
        'animaux_autorises' => 'boolean',
        'sous_location_autorisee' => 'boolean',
        'renouvellement_automatique' => 'boolean',
        'etat_lieux_entree' => 'array',
        'etat_lieux_sortie' => 'array',
        'documents_annexes' => 'array',

    ];

    // Relations
    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    // Accesseurs
    public function getLoyerMensuelFormatAttribute()
    {
        return number_format($this->loyer_mensuel, 0, ',', ' ') . ' FCFA';
    }

    public function getCautionFormatAttribute()
    {
        return number_format($this->caution, 0, ',', ' ') . ' FCFA';
    }

    public function getChargesMensuelsFormatAttribute()
    {
        return number_format($this->charges_mensuelles, 0, ',', ' ') . ' FCFA';
    }

    public function getTotalMensuelAttribute()
    {
        return $this->loyer_mensuel + $this->charges_mensuelles;
    }

    public function getTotalMensuelFormatAttribute()
    {
        return number_format($this->total_mensuel, 0, ',', ' ') . ' FCFA';
    }

    public function getJoursRestantsAttribute()
    {
        return $this->date_fin ? now()->diffInDays($this->date_fin, false) : null;
    }

    public function getMoisRestantsAttribute()
    {
        return $this->date_fin ? now()->diffInMonths($this->date_fin, false) : null;
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeExpires($query)
    {
        return $query->where('statut', 'expire');
    }

    public function scopeBrouillons($query)
    {
        return $query->where('statut', 'brouillon');
    }

    public function scopeResilies($query)
    {
        return $query->where('statut', 'resilie');
    }

    public function scopeARenouveler($query, $joursAvant = 90)
    {
        $dateLimit = now()->addDays($joursAvant);
        return $query->where('statut', 'actif')
                    ->where('date_fin', '<=', $dateLimit);
    }

    public function scopeRecherche($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('numero', 'like', "%{$search}%")
              ->orWhereHas('bien', function($q) use ($search) {
                  $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('adresse', 'like', "%{$search}%");
              })
              ->orWhereHas('locataire', function($q) use ($search) {
                  $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenoms', 'like', "%{$search}%");
              });
        });
    }

    // Méthodes
    public function estActif()
    {
        return $this->statut === 'actif';
    }

    public function estExpire()
    {
        return $this->statut === 'expire' || ($this->date_fin && $this->date_fin->isPast());
    }

    public function estResilie()
    {
        return $this->statut === 'resilie';
    }

    public function prochEcheanceRenouvellement()
    {
        if (!$this->estActif()) {
            return false;
        }
        
        $joursAvant = $this->preavis_jours ?? 90;
        $dateLimit = now()->addDays($joursAvant);
        
        return $this->date_fin <= $dateLimit;
    }

    public function genererNumero()
    {
        $lastContrat = static::orderBy('id', 'desc')->first();
        $number = $lastContrat ? $lastContrat->id + 1 : 1;
        return 'CONT-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function calculerDuree()
    {
        if ($this->date_debut && $this->date_fin) {
            return $this->date_debut->diffInMonths($this->date_fin);
        }
        return 0;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contrat) {
            if (empty($contrat->numero)) {
                $contrat->numero = $contrat->genererNumero();
            }
            
            if ($contrat->date_debut && $contrat->date_fin && !$contrat->duree_mois) {
                $contrat->duree_mois = $contrat->calculerDuree();
            }
        });

        static::updating(function ($contrat) {
            // Vérifier si le contrat est expiré
            if ($contrat->estActif() && $contrat->date_fin && $contrat->date_fin->isPast()) {
                $contrat->statut = 'expire';
            }
        });
    }

    
}