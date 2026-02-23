<?php

namespace App\Services;

use App\Models\Atleta;
use Carbon\Carbon;

class PerfilAtletaService
{
    public function montarDados(Atleta $atleta): array
    {
        $alturaFmt = $this->formatarAltura($atleta->altura);
        $pesoFmt = $this->formatarPeso($atleta->peso);

        $idade = $atleta->idade;
        if (empty($idade) && !empty($atleta->data_nascimento)) {
            $idade = Carbon::parse($atleta->data_nascimento)->age;
        }

        $contatoRaw = trim((string) ($atleta->contato ?? ''));
        $contatoDigitos = preg_replace('/\D+/', '', $contatoRaw);
        $whatsappUrl = null;
        if (strlen($contatoDigitos) >= 10) {
            $whatsNumero = ltrim($contatoDigitos, '0');
            $whatsNumero = str_starts_with($whatsNumero, '55') ? $whatsNumero : '55' . $whatsNumero;
            $whatsappUrl = 'https://wa.me/' . $whatsNumero;
        }
        $emailUrl = filter_var($contatoRaw, FILTER_VALIDATE_EMAIL) ? 'mailto:' . $contatoRaw : null;

        $video = $this->parseVideoLink((string) ($atleta->resumo ?? ''));

        $rankPosicao = Atleta::query()
            ->where('visualizacoes', '>', (int) ($atleta->visualizacoes ?? 0))
            ->count() + 1;

        $perfil = [
            'id' => $atleta->id,
            'nome' => $atleta->nome_completo ?: 'Atleta sem nome',
            'posicao' => $atleta->posicao_jogo ?: 'Posicao nao informada',
            'idade' => $idade ?: '-',
            'altura' => $alturaFmt,
            'peso' => $pesoFmt,
            'cidade' => $atleta->cidade ?: 'Cidade nao informada',
            'entidade' => $atleta->entidade ?: 'Entidade nao informada',
            'foto_url' => !empty($atleta->imagem_base64)
                ? 'data:image/png;base64,' . $atleta->imagem_base64
                : asset('img/avatar.png'),
            'bio' => $video['bio'],
            'visualizacoes' => (int) ($atleta->visualizacoes ?? 0),
            'rank' => $rankPosicao,
            'whatsapp_url' => $whatsappUrl,
            'email_url' => $emailUrl,
            'video_original_url' => $video['video_original_url'],
            'video_embed_url' => $video['video_embed_url'],
            'video_tipo' => $video['video_tipo'],
        ];

        $stats = [
            ['label' => 'Visualizacoes', 'valor' => number_format($perfil['visualizacoes'], 0, ',', '.')],
            ['label' => 'Idade', 'valor' => is_numeric($perfil['idade']) ? $perfil['idade'] . ' anos' : $perfil['idade']],
            ['label' => 'Altura', 'valor' => $perfil['altura']],
            ['label' => 'Peso', 'valor' => $perfil['peso']],
        ];

        $destaques = [
            'Perfil visualizado: ' . number_format((int) $perfil['visualizacoes'], 0, ',', '.') . ' vezes',
            'Rank atual: #' . $perfil['rank'],
            'Treina na instituicao: ' . ($perfil['entidade'] ?: '-'),
        ];

        return [
            'atleta' => $perfil,
            'stats' => $stats,
            'destaques' => $destaques,
        ];
    }

    private function formatarAltura($altura): string
    {
        $alturaNum = is_numeric($altura) ? (float) $altura : 0.0;
        if (abs($alturaNum) >= 10) {
            $alturaNum = $alturaNum / 100;
        }

        return $alturaNum > 0 ? number_format($alturaNum, 2, ',', '.') . ' m' : '-';
    }

    private function formatarPeso($peso): string
    {
        $pesoNum = is_numeric($peso) ? (float) $peso : 0.0;
        return $pesoNum > 0 ? number_format($pesoNum, 1, ',', '.') . ' kg' : '-';
    }

    private function parseVideoLink(string $resumo): array
    {
        $videoRaw = trim($resumo);
        $videoOriginalUrl = filter_var($videoRaw, FILTER_VALIDATE_URL) ? $videoRaw : null;
        $videoEmbedUrl = null;
        $videoTipo = null;

        if ($videoOriginalUrl) {
            $parsed = parse_url($videoOriginalUrl);
            $host = strtolower((string) ($parsed['host'] ?? ''));
            $path = (string) ($parsed['path'] ?? '');
            parse_str((string) ($parsed['query'] ?? ''), $queryParams);

            if (str_contains($host, 'youtube.com') || str_contains($host, 'youtu.be')) {
                $videoId = null;
                if (str_contains($host, 'youtu.be')) {
                    $videoId = trim($path, '/');
                } elseif (!empty($queryParams['v'])) {
                    $videoId = $queryParams['v'];
                } elseif (preg_match('#/(shorts|embed)/([^/?]+)#', $path, $matches)) {
                    $videoId = $matches[2] ?? null;
                }

                if (!empty($videoId)) {
                    $videoEmbedUrl = 'https://www.youtube.com/embed/' . rawurlencode($videoId);
                    $videoTipo = 'iframe';
                }
            } elseif (str_contains($host, 'vimeo.com')) {
                if (preg_match('#/(\d+)#', $path, $matches)) {
                    $videoEmbedUrl = 'https://player.vimeo.com/video/' . $matches[1];
                    $videoTipo = 'iframe';
                }
            }

            if (empty($videoTipo)) {
                if (preg_match('/\.(mp4|webm|ogg)(\?.*)?$/i', $videoOriginalUrl)) {
                    $videoTipo = 'file';
                } else {
                    $videoTipo = 'external';
                }
            }
        }

        $bio = (!empty($videoRaw) && empty($videoOriginalUrl))
            ? $videoRaw
            : 'Perfil do atleta com dados reais da vitrine.';

        return [
            'bio' => $bio,
            'video_original_url' => $videoOriginalUrl,
            'video_embed_url' => $videoEmbedUrl,
            'video_tipo' => $videoTipo,
        ];
    }
}
