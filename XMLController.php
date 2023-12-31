<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class XMLController extends Controller
{

    public function static_pages()
    {
        $app_url = 'http://localhost';
        return [
            ['1.0', $app_url.'/'],
            ['0.95', $app_url.'/about-us'],
            ['0.95', $app_url.'/services'],
            ['0.95', $app_url.'/projects'],
            ['0.95', $app_url.'/our-team'],
            ['0.95', $app_url.'/contact-us'],
            ['0.95', $app_url.'/blog'],
        ];
    }

    public function index()
    {

        $xw = xmlwriter_open_memory();
        xmlwriter_set_indent($xw, 1);
        xmlwriter_set_indent_string($xw, ' ');

        xmlwriter_start_document($xw, '1.0', 'UTF-8');

        // A first element
        xmlwriter_start_element($xw, 'urlset');

        // Attribute for urlset
        xmlwriter_start_attribute($xw, 'xmlns');
        xmlwriter_text($xw, 'http://www.sitemaps.org/schemas/sitemap/0.9');
        xmlwriter_end_attribute($xw);

        // Attribute for urlset
        xmlwriter_start_attribute($xw, 'xmlns:xsi');
        xmlwriter_text($xw, 'http://www.w3.org/2001/XMLSchema-instance');
        xmlwriter_end_attribute($xw);

        // Attribute for urlset
        xmlwriter_start_attribute($xw, 'xsi:schemaLocation');
        xmlwriter_text($xw, 'http://www.w3.org/2001/XMLSchema-instance http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');
        xmlwriter_end_attribute($xw);

        // Start a child element, parnet = urlset
        foreach ($this->static_pages() as $page) {
            xmlwriter_start_element($xw, 'url');

            xmlwriter_start_element($xw, 'loc');
            xmlwriter_text($xw, $page[1]);
            xmlwriter_end_element($xw);

            xmlwriter_start_element($xw, 'lastmod');
            xmlwriter_text($xw, Carbon::now('GMT')->format('Y-m-d\Th:m:s') . '+00:00');
            xmlwriter_end_element($xw);

            xmlwriter_start_element($xw, 'priority');
            xmlwriter_text($xw, $page[0]);
            xmlwriter_end_element($xw);

            xmlwriter_end_element($xw);
        }

        // End urlset element
        xmlwriter_end_element($xw);

        xmlwriter_end_document($xw);

        return response(xmlwriter_output_memory($xw), 200)->header('Content-Type', 'application/xml');
    }
}
