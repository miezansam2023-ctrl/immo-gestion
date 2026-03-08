<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incident extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero',
        'bien_id',
        'contrat_id',
        'locataire_id',
        'gestionnaire_id',
        'categorie',
        'titre',
        'description',
        'priorite',
        'impact',
        'date_signalement',
        'date_intervention',
        'date_resolution',
        'prestataire_nom',
        'prestataire_telephone',
        'travaux_effectues',
        'cout_estime',
        'cout_reel',
        'charge_par',
        'statut',
        'photos',
        'documents',
        'notes',
        'historique',
        'note_satisfaction',
        'commentaire_satisfaction',
    ];

    protected $casts = [
        'date_signalement' => 'datetime',
        'date_intervention' => 'datetime',
        'date_resolution' => 'datetime',
        'cout_estime' => 'decimal:2',
        'cout_reel' => 'decimal:2',
        'photos' => 'array',
        'documents' => 'array',
        'historique' => 'array',
    ];

    // Relations
    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }

    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    // Accesseurs
    public function getCoutEstimeFormatAttribute()
    {
        return number_format($this->cout_estime, 0, ',', ' ') . ' FCFA';
    }

    public function getCoutReelFormatAttribute()
    {
        return number_format($this->cout_reel, 0, ',', ' ') . ' FCFA';
    }

    public function getCategorieLibelleAttribute()
    {
        $categories = [
            'plomberie' => 'Plomberie',
            'electricite' => 'Électricité',
            'climatisation' => 'Climatisation',
            'menuiserie' => 'Menuiserie',
            'peinture' => 'Peinture',
            'toiture' => 'Toiture',
            'portail' => 'Portail/Clôture',
            'jardin' => 'Jardin',
            'autre' => 'Autre',
        ];
        
        return $categories[$this->categorie] ?? $this->categorie;
    }

    public function getPrioriteLibelleAttribute()
    {
        $priorites = [
            'basse' => 'Basse',
            'moyenne' => 'Moyenne',
            'haute' => 'Haute',
            'urgente' => 'Urgente',
        ];
        
        return $priorites[$this->priorite] ?? $this->priorite;
    }

    public function getPrioriteBadgeClassAttribute()
    {
        $classes = [
            'basse' => 'bg-gray-100 text-gray-800',
            'moyenne' => 'bg-blue-100 text-blue-800',
            'haute' => 'bg-orange-100 text-orange-800',
            'urgente' => 'bg-red-100 text-red-800',
        ];
        
        return $classes[$this->priorite] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatutLibelleAttribute()
    {
        $statuts = [
            'nouveau' => 'Nouveau',
            'en_attente' => 'En attente',
            'en_cours' => 'En cours',
            'resolu' => 'Résolu',
            'annule' => 'Annulé',
            'reporte' => 'Reporté',
        ];
        
        return $statuts[$this->statut] ?? $this->statut;
    }

    public function getStatutBadgeClassAttribute()
    {
        $classes = [
            'nouveau' => 'bg-yellow-100 text-yellow-800',
            'en_attente' => 'bg-blue-100 text-blue-800',
            'en_cours' => 'bg-orange-100 text-orange-800',
            'resolu' => 'bg-green-100 text-green-800',
            'annule' => 'bg-gray-100 text-gray-800',
            'reporte' => 'bg-purple-100 text-purple-800',
        ];
        
        return $classes[$this->statut] ?? 'bg-gray-100 text-gray-800';
    }

    public function getDelaiResolutionAttribute()
    {
        if ($this->date_resolution) {
            return $this->date_signalement->diffInDays($this->date_resolution);
        }
        
        return $this->date_signalement->diffInDays(now());
    }

    // Scopes
    public function scopeNouveaux($query)
    {
        return $query->where('statut', 'nouveau');
    }

    public function scopeEnCours($query)
    {
        return $query->whereIn('statut', ['en_attente', 'en_cours']);
    }

    public function scopeResolus($query)
    {
        return $query->where('statut', 'resolu');
    }

    public function scopeUrgents($query)
    {
        return $query->where('priorite', 'urgente');
    }

    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    public function scopeParPriorite($query, $priorite)
    {
        return $query->where('priorite', $priorite);
    }

    public function scopeRecherche($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('numero', 'like', "%{$search}%")
              ->orWhere('titre', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('bien', function($q) use ($search) {
                  $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('adresse', 'like', "%{$search}%");
              });
        });
    }

    // Méthodes
    public function estResolu()
    {
        return $this->statut === 'resolu';
    }

    public function estEnCours()
    {
        return in_array($this->statut, ['en_attente', 'en_cours']);
    }

    public function estUrgent()
    {
        return $this->priorite === 'urgente';
    }

    public function marquerCommeResolu($travauxEffectues = null, $coutReel = null)
    {
        $this->statut = 'resolu';
        $this->date_resolution = now();
        
        if ($travauxEffectues) {
            $this->travaux_effectues = $travauxEffectues;
        }
        
        if ($coutReel !== null) {
            $this->cout_reel = $coutReel;
        }
        
        $this->ajouterHistorique('Incident résolu');
        $this->save();
        
        return $this;
    }

    public function ajouterHistorique($message, $details = null)
    {
        $historique = $this->historique ?? [];
        
        $historique[] = [
            'date' => now()->toDateTimeString(),
            'utilisateur' => auth()->user()->nom_complet ?? 'Système',
            'message' => $message,
            'details' => $details,
        ];
        
        $this->historique = $historique;
        
        return $this;
    }

    public function genererNumero()
    {
        $lastIncident = static::orderBy('id', 'desc')->first();
        $number = $lastIncident ? $lastIncident->id + 1 : 1;
        return 'INC-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($incident) {
            if (empty($incident->numero)) {
                $incident->numero = $incident->genererNumero();
            }
            
            if (!$incident->date_signalement) {
                $incident->date_signalement = now();
            }
        });
    }
}