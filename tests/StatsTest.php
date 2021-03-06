<?php

use Balsama\DrupalOrgProject\Stats;
use PHPHtmlParser\Dom;

class StatsTest extends PHPUnit_Framework_TestCase {

    /**
     * The dom and stats_dom variables are set and that they are
     * PHPHtmlParser\Dom objects.
     */
    public function testDomObjects() {
        $project_name = 'ctools';
        $project = new Stats($project_name);

        $dom = $project->getDom();
        $this->assertInstanceOf('PHPHtmlParser\Dom', $dom);

        $stats_dom = $project->getStatsDom();
        $this->assertInstanceOf('PHPHtmlParser\Dom', $stats_dom);
    }

    /**
     * Usage statistics are retrieved and within the expected range.
     */
    public function testUsageStatistics() {
        // Project with two columns; 7.x & 8.x.
        $project_name = 'metatag';
        $project = new Stats($project_name, true);

        $usage = $project->getCurrentD8Usage();
        $this->assertInternalType('int', $usage);
        $this->assertTrue($usage > 20000);
        $this->assertTrue($usage < 2000000);

        $d7usage = $project->getCurrentD7Usage();
        $this->assertInternalType('int', $d7usage);
        $this->assertTrue($d7usage > 290000);
        $this->assertTrue($d7usage < 2000000);

        // Project with four columns; 5.x, 6.x, 7.x, & 8.x.
        $project_name = 'pathauto';
        $project = new Stats($project_name, true);

        $usage = $project->getCurrentD8Usage();
        $this->assertInternalType('int', $usage);
        $this->assertTrue($usage > 19000);
        $this->assertTrue($usage < 2000000);

        $d7usage = $project->getCurrentD7Usage();
        $this->assertInternalType('int', $d7usage);
        $this->assertTrue($d7usage > 600000);
        $this->assertTrue($d7usage < 2000000);
    }

    /**
     * Projects with no nth release don't return usage statistics for that
     * release.
     */
    public function testNoReleaseStatistics() {
        // Facet API was renamed facets, so it should never have a D8 release.
        $project_name = 'facetapi';
        $project = new Stats($project_name);

        $d8_usage = $project->getCurrentD8Usage();
        $this->assertFalse(boolval($d8_usage));
    }

    /**
     * Proper stability is returned, including dev branches.
     */
    public function testDevReleaseStatus() {
        // Admin menu has a dev branch, but no further development. If they ever
        // tag something there, this test should fail.
        // Facet API was renamed facets, so it should never have a D8 release.
        $project_name = 'admin_menu';
        $project = new Stats($project_name);

        $status = $project->getD8Stability();
        echo '|||STATUS: ' . $status . '|||';
        $this->assertTrue($status === 'dev');

        // @todo test to make sure full release and other regex still work.

    }
 }