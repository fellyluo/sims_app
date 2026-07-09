<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PanduanController extends Controller
{
    private const SOURCE = 'docs/PANDUAN_PENGGUNAAN_SIMS_APP.md';

    public function index(): View
    {
        $path = base_path(self::SOURCE);

        abort_unless(File::exists($path), 404, 'Panduan penggunaan belum tersedia.');

        $markdown = (string) File::get($path);
        $parsed = $this->parseMarkdown($markdown);

        return view('panduan.index', [
            'docTitle' => $parsed['title'],
            'introHtml' => $parsed['introHtml'],
            'sections' => $parsed['sections'],
            'sourcePath' => self::SOURCE,
            'lastUpdated' => date('d M Y H:i', File::lastModified($path)),
        ]);
    }

    private function parseMarkdown(string $markdown): array
    {
        $markdown = preg_replace("/\r\n?/", "\n", $markdown) ?? $markdown;
        $title = 'Panduan Penggunaan SIMS';

        if (preg_match('/^#\s+(.+)$/m', $markdown, $match)) {
            $title = $this->plainHeading($match[1]);
        }

        preg_match_all('/^##\s+(.+)$/m', $markdown, $matches, PREG_OFFSET_CAPTURE);

        $sections = [];
        $usedSlugs = [];
        $introMarkdown = $markdown;

        if (!empty($matches[0])) {
            $introMarkdown = substr($markdown, 0, $matches[0][0][1]);

            foreach ($matches[0] as $index => $match) {
                $start = $match[1];
                $end = $matches[0][$index + 1][1] ?? strlen($markdown);
                $sectionMarkdown = trim(substr($markdown, $start, $end - $start));
                $headings = $this->headings($sectionMarkdown, $usedSlugs);
                $sectionTitle = $headings[0]['title'] ?? $this->plainHeading($matches[1][$index][0]);
                $sectionId = $headings[0]['id'] ?? $this->uniqueSlug($sectionTitle, $usedSlugs);
                $html = $this->markdownToHtml($sectionMarkdown, array_column($headings, 'id'));

                $sections[] = [
                    'id' => $sectionId,
                    'title' => $sectionTitle,
                    'html' => $html,
                    'search' => Str::lower($sectionTitle.' '.strip_tags($html)),
                    'subsections' => array_values(array_filter(
                        $headings,
                        fn (array $heading) => $heading['level'] === 3
                    )),
                ];
            }
        }

        $introMarkdown = trim(preg_replace('/^#\s+.+\n?/m', '', $introMarkdown, 1) ?? $introMarkdown);

        return [
            'title' => $title,
            'introHtml' => $introMarkdown !== '' ? $this->markdownToHtml($introMarkdown, []) : '',
            'sections' => $sections,
        ];
    }

    private function headings(string $markdown, array &$usedSlugs): array
    {
        preg_match_all('/^(#{2,3})\s+(.+)$/m', $markdown, $matches);

        $headings = [];

        foreach ($matches[1] as $index => $marks) {
            $title = $this->plainHeading($matches[2][$index]);
            $headings[] = [
                'level' => strlen($marks),
                'title' => $title,
                'id' => $this->uniqueSlug($title, $usedSlugs),
            ];
        }

        return $headings;
    }

    private function markdownToHtml(string $markdown, array $headingIds): string
    {
        $html = Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $index = 0;

        return preg_replace_callback('/<h([23])>(.*?)<\/h\1>/s', function (array $match) use (&$index, $headingIds) {
            $id = $headingIds[$index] ?? null;
            $index++;

            if (!$id) {
                return $match[0];
            }

            return '<h'.$match[1].' id="'.e($id).'">'.$match[2].'</h'.$match[1].'>';
        }, $html) ?? $html;
    }

    private function plainHeading(string $heading): string
    {
        $heading = preg_replace('/\s+#+$/', '', trim($heading)) ?? trim($heading);
        $heading = preg_replace('/\[(.*?)\]\((.*?)\)/', '$1', $heading) ?? $heading;
        $heading = str_replace(['`', '*', '_'], '', $heading);

        return trim($heading);
    }

    private function uniqueSlug(string $title, array &$usedSlugs): string
    {
        $base = Str::slug($title) ?: 'bagian';
        $slug = $base;
        $counter = 2;

        while (isset($usedSlugs[$slug])) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        $usedSlugs[$slug] = true;

        return $slug;
    }
}
