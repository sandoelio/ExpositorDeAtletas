<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Atleta extends Model
{
    protected $table = 'atletas';

    protected $fillable = [
        'imagem_base64',
        'nome_completo',
        'data_nascimento',
        'idade',
        'sexo',
        'altura',
        'peso',
        'cidade',
        'entidade',
        'posicao_jogo',
        'contato',
        'email',
        'resumo',
    ];

    /**
     * Mutator para data_nascimento.
     * Sempre que atribuirmos uma nova data de nascimento,
     * a coluna 'idade' será calculada e preenchida automaticamente.
     */
    public function setDataNascimentoAttribute($value)
    {
        // Armazena a data de nascimento
        $this->attributes['data_nascimento'] = $value;

        // Calcula a idade e preenche o atributo
        $this->attributes['idade'] = Carbon::parse($value)->age;
    }

    /**
     * Accessor para ler a idade diretamente.
     * Pode ser removido se você não precisar de lógica adicional
     * ao obter esse valor.
     */
    public function getIdadeAttribute($value)
    {
        return $value;
    }

    public function scopeBuscarPorNomeFlexivel(Builder $query, ?string $texto): Builder
    {
        $textoNormalizado = static::normalizarTextoBusca($texto);

        if ($textoNormalizado === '') {
            return $query;
        }

        $termos = array_values(array_filter(explode(' ', $textoNormalizado), static fn (string $termo) => $termo !== ''));
        $expressaoNormalizada = static::sqlNomeCompletoNormalizado();
        $expressaoCompacta = "REPLACE($expressaoNormalizada, ' ', '')";
        $textoCompacto = str_replace(' ', '', $textoNormalizado);

        return $query->where(function (Builder $subQuery) use ($termos, $expressaoNormalizada, $expressaoCompacta, $textoCompacto) {
            foreach ($termos as $termo) {
                $subQuery->whereRaw("$expressaoNormalizada LIKE ?", ['%' . $termo . '%']);
            }

            if ($textoCompacto !== '') {
                $subQuery->orWhereRaw("$expressaoCompacta LIKE ?", ['%' . $textoCompacto . '%']);
            }
        });
    }

    public static function normalizarTextoBusca(?string $texto): string
    {
        $texto = trim((string) $texto);

        if ($texto === '') {
            return '';
        }

        $texto = Str::lower(Str::ascii($texto));
        $texto = preg_replace('/[^a-z0-9]+/u', ' ', $texto) ?? $texto;
        $texto = preg_replace('/\s+/u', ' ', $texto) ?? $texto;

        return trim($texto);
    }

    private static function sqlNomeCompletoNormalizado(): string
    {
        $expressao = "COALESCE(nome_completo, '')";

        foreach (static::searchNormalizationMap() as $original => $substituto) {
            $originalEscapado = str_replace("'", "''", $original);
            $substitutoEscapado = str_replace("'", "''", $substituto);
            $expressao = "REPLACE($expressao, '$originalEscapado', '$substitutoEscapado')";
        }

        $expressao = "LOWER($expressao)";
        $expressao = "TRIM($expressao)";
        $expressao = "REPLACE(REPLACE(REPLACE($expressao, '  ', ' '), '  ', ' '), '  ', ' ')";

        return $expressao;
    }

    private static function searchNormalizationMap(): array
    {
        return [
            mb_chr(0x00C1, 'UTF-8') => 'a', mb_chr(0x00C0, 'UTF-8') => 'a', mb_chr(0x00C2, 'UTF-8') => 'a', mb_chr(0x00C3, 'UTF-8') => 'a', mb_chr(0x00C4, 'UTF-8') => 'a',
            mb_chr(0x00E1, 'UTF-8') => 'a', mb_chr(0x00E0, 'UTF-8') => 'a', mb_chr(0x00E2, 'UTF-8') => 'a', mb_chr(0x00E3, 'UTF-8') => 'a', mb_chr(0x00E4, 'UTF-8') => 'a',
            mb_chr(0x00C9, 'UTF-8') => 'e', mb_chr(0x00C8, 'UTF-8') => 'e', mb_chr(0x00CA, 'UTF-8') => 'e', mb_chr(0x00CB, 'UTF-8') => 'e',
            mb_chr(0x00E9, 'UTF-8') => 'e', mb_chr(0x00E8, 'UTF-8') => 'e', mb_chr(0x00EA, 'UTF-8') => 'e', mb_chr(0x00EB, 'UTF-8') => 'e',
            mb_chr(0x00CD, 'UTF-8') => 'i', mb_chr(0x00CC, 'UTF-8') => 'i', mb_chr(0x00CE, 'UTF-8') => 'i', mb_chr(0x00CF, 'UTF-8') => 'i',
            mb_chr(0x00ED, 'UTF-8') => 'i', mb_chr(0x00EC, 'UTF-8') => 'i', mb_chr(0x00EE, 'UTF-8') => 'i', mb_chr(0x00EF, 'UTF-8') => 'i',
            mb_chr(0x00D3, 'UTF-8') => 'o', mb_chr(0x00D2, 'UTF-8') => 'o', mb_chr(0x00D4, 'UTF-8') => 'o', mb_chr(0x00D5, 'UTF-8') => 'o', mb_chr(0x00D6, 'UTF-8') => 'o',
            mb_chr(0x00F3, 'UTF-8') => 'o', mb_chr(0x00F2, 'UTF-8') => 'o', mb_chr(0x00F4, 'UTF-8') => 'o', mb_chr(0x00F5, 'UTF-8') => 'o', mb_chr(0x00F6, 'UTF-8') => 'o',
            mb_chr(0x00DA, 'UTF-8') => 'u', mb_chr(0x00D9, 'UTF-8') => 'u', mb_chr(0x00DB, 'UTF-8') => 'u', mb_chr(0x00DC, 'UTF-8') => 'u',
            mb_chr(0x00FA, 'UTF-8') => 'u', mb_chr(0x00F9, 'UTF-8') => 'u', mb_chr(0x00FB, 'UTF-8') => 'u', mb_chr(0x00FC, 'UTF-8') => 'u',
            mb_chr(0x00C7, 'UTF-8') => 'c', mb_chr(0x00E7, 'UTF-8') => 'c',
            mb_chr(0x00D1, 'UTF-8') => 'n', mb_chr(0x00F1, 'UTF-8') => 'n',
            "'" => ' ', '"' => ' ', '`' => ' ', mb_chr(0x00B4, 'UTF-8') => ' ',
            '-' => ' ', '_' => ' ', '.' => ' ', ',' => ' ', '/' => ' ',
        ];
    }
}
