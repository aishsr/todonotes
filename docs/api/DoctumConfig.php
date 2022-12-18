<?php declare(strict_types = 1);

error_reporting(E_ALL & ~E_DEPRECATED);

use Doctum\Doctum;
use Doctum\Parser\Filter\TrueFilter;
use Doctum\RemoteRepository\GitHubRemoteRepository;
use Doctum\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

// set true for more output
$debug = false;

// /path/to/yourlib/src
$dir = __DIR__ . '/../../';

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('resources')
    ->exclude('tests')
    ->exclude('storage')
    ->exclude('vendor')
    ->exclude('docs')
    ->in($dir);

if (true === $debug) {
    foreach ($iterator as $file) {
        echo $file->getRealPath() . "\n";
    }
}

// Get the current branch
$stringfromfile = file("${dir}/.git/HEAD", FILE_USE_INCLUDE_PATH);
$firstLine = $stringfromfile[0];
// seperate out by the "/" in the string
$explodedstring = explode('/', $firstLine, 3);
$branchname = trim($explodedstring[2]);

// generate documentation for all v2.0.* tags, the 2.0 branch, and the main one
$versions = GitVersionCollection::create($dir);
$versions->setSorter(
    function ($a, $b) {
        return version_compare($a, $b);
    },
);
$versions->addFromTags('v*')
    ->add('master', 'main branch')
    ->add('dev', 'dev branch')
    ->add('stg', 'stage branch')
    ->add('uat', 'UAT branch');
// Make sure this is last so we end up on the same branch
$versions->add($branchname, 'current branch');

$doctum = new Doctum(
    $iterator,
    [
        'versions' => $versions,
        'title' => 'Scrawlr Backend API Core API',
        'language' => 'en',
        'build_dir' => __DIR__ . '/build/%version%',
        'cache_dir' => __DIR__ . '/cache/%version%',
        'source_dir' => $dir,
        'remote_repository' => new GitHubRemoteRepository('username/repository', $dir),
        'default_opened_level' => 2,
        'footer_link' => [
            'href' => 'https://github.com/scrawlr/be-api-core',
            'rel' => 'noreferrer noopener',
            'target' => '_blank',
            'before_text' => 'You can edit the configuration',
            'link_text' => 'on this',
            'after_text' => 'repository',
        ],
    ],
);

// document all methods and properties
$doctum['filter'] = function () {
    return new TrueFilter();
};

return $doctum;
