<?php

use Illuminate\Support\Str;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Spatie\CommonMarkHighlighter\FencedCodeRenderer;
use Spatie\CommonMarkHighlighter\IndentedCodeRenderer;

if (!function_exists('md2html')) {
    /**
     * Converte markdown para html (github flavored)
     *
     * @param String $markdown
     * @param String $style Estido do CSS (default=default.css)
     * @return String
     * @author Masakik, em 16/11/2022
     */
    function md2html($markdown, $style = 'default.css')
    {
        $environment = new Environment();
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addRenderer(FencedCode::class, new FencedCodeRenderer());
        $environment->addRenderer(IndentedCode::class, new IndentedCodeRenderer());

        $markdownConverter = new MarkdownConverter($environment);

        $html = '<style>' . file_get_contents(base_path('vendor/scrivo/highlight.php/styles/' . $style)) . '</style>';
        $html .= $markdownConverter->convertToHtml($markdown);
        return $html;
    }

    if (!function_exists('formatarData')) {
        function formatarData($data)
        {
            if ($data->format('d/m/Y') == date('d/m/Y')) {
                return 'Hoje, ' . $data->format('H:i');
            } else {
                return $data->format('d \d\e M.');
            }
        }
    }

    if (!function_exists('getModeloTiposArquivo')) {
        function getModeloTiposArquivo($modelo)
        {
            $modeloClasse = "App\\Models\\" . Str::studly($modelo);
            if (method_exists($modeloClasse, 'tiposArquivo')) {
                return $modeloClasse::tiposArquivo();
            }
            return [];
        }
    }

    if (!function_exists('fixJson')) {
        function fixJson($json) {
            return trim(json_encode($json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), '"');
        }
    }
}
