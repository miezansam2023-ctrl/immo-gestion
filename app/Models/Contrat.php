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
        'date_resiliation' => 'datetime',
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

    public function getDateFinFrAttribute()
    {
        if (!$this->date_fin) {
            return null;
        }

        return $this->date_fin->locale('fr')->translatedFormat('d M Y');
    }

    public function getDateSignatureFrAttribute()
    {
        if (!$this->date_signature) {
            return null;
        }

        return $this->date_signature->locale('fr')->translatedFormat('d MMMM Y');
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

    public function getDateResiliationFrAttribute()
    {
        if (!$this->date_resiliation) {
            return null;
        }

        return $this->date_resiliation->locale('fr')->translatedFormat('d M Y');
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
        $year = date('Y');
        $maxAttempts = 100;
        $attempt = 0;

        do {
            // Récupérer le dernier numéro de l'année courante (incluant les soft deletes)
            $lastNumero = static::withTrashed()
                ->where('numero', 'like', "CONT-{$year}-%")
                ->orderByRaw("CAST(SUBSTRING_INDEX(numero, '-', -1) AS UNSIGNED) DESC")
                ->value('numero');

            // Extraire le numéro séquentiel
            if ($lastNumero) {
                preg_match('/CONT-\d+-(\d+)$/', $lastNumero, $matches);
                $number = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
            } else {
                $number = 1;
            }

            $numero = 'CONT-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
            $attempt++;

            // Vérifier que ce numéro n'existe pas (y compris les soft deleted)
            $exists = static::withTrashed()
                ->where('numero', $numero)
                ->exists();

            if (!$exists) {
                return $numero;
            }

            // Incrémenter et réessayer
            $number++;
        } while ($attempt < $maxAttempts);

        throw new \Exception('Impossible de générer un numéro de contrat unique après ' . $maxAttempts . ' tentatives');
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
                $maxAttempts = 5;
                $attempt = 0;
                $numero = null;

                // Réessayer jusqu'à 5 fois en cas de conflit
                do {
                    $numero = $contrat->genererNumero();
                    
                    // Vérifier qu'aucun autre contrat ne porte ce numéro
                    $exists = static::where('numero', $numero)->exists();
                    
                    if (!$exists) {
                        $contrat->numero = $numero;
                        break;
                    }
                    
                    $attempt++;
                    if ($attempt < $maxAttempts) {
                        // Attendre un peu avant de réessayer
                        usleep(rand(100, 500) * 1000); // 100-500ms
                    }
                } while ($attempt < $maxAttempts);

                if ($attempt >= $maxAttempts) {
                    throw new \Exception('Impossible de générer un numéro de contrat unique après ' . $maxAttempts . ' tentatives');
                }
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