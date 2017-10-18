<?php

namespace Drupal\Tests\backup_migrate\Functional;

use Drupal\Tests\BrowserTestBase;


/**
 * Checks if pages loads successfully
 *
 * @group backup_migrate
 */
class BackupMigratePageLoadTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['backup_migrate'];

  /**
   * {@inheritdoc}
   */
  protected $strictConfigSchema = FALSE;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->container->get('router.builder')->rebuild();
  }

  /**
   * Tests if site quick backup function loads
   */
  public function testPages() {

    //Ensure backup_migrate folder exists. `admin/config/development/backup_migrate/backups` url will fail without this.
    $path = 'private://backup_migrate/';

    file_prepare_directory($path, FILE_CREATE_DIRECTORY);

    $account = $this->drupalCreateUser([
      'perform backup',
      'administer backup and migrate',
      'restore from backup',
      'delete backup files',
      'access backup files',
    ]);
    $this->drupalLogin($account);

    $urls = [
      '/admin/config/development/backup_migrate' => ['text' => 'Quick Backup'],
      'admin/config/development/backup_migrate/advanced' => ['text' => 'Advanced Backup'],
      'admin/config/development/backup_migrate/restore' => ['text' => 'Restore'],
      'admin/config/development/backup_migrate/backups' => ['text' => 'Backups'],
      'admin/config/development/backup_migrate/schedule' => ['text' => 'Schedule'],
      'admin/config/development/backup_migrate/schedule/add' => ['text' => 'Add schedule'],
      'admin/config/development/backup_migrate/settings' => ['text' => 'Settings'],
      'admin/config/development/backup_migrate/settings/add' => ['text' => 'Add settings profile'],
      'admin/config/development/backup_migrate/settings/destination' => ['text' => 'Backup Destination'],
      'admin/config/development/backup_migrate/settings/destination/add' => ['text' => 'Add destination'],
      'admin/config/development/backup_migrate/settings/source' => ['text' => 'Backup sources'],
      'admin/config/development/backup_migrate/settings/source/add' => ['text' => 'Add Backup Source'],
    ];

    foreach ($urls as $url => $settings) {
      $this->drupalGet($url);
      $this->assertSession()->statusCodeEquals(200);
      $this->assertSession()->pageTextContains($settings['text']);
    }
  }

}
