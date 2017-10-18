<?php

namespace Drupal\Tests\backup_migrate\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests backup migrate permissions functionality.
 *
 * @group backup_migrate
 */
class BackupMigratePermissionsTest extends BrowserTestBase {

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
  }

  /**
   * Tests access for anonymous users
   */
  public function testAnonymous() {
    $this->drupalGet('admin/config/development/backup_migrate');
    $this->assertSession()->statusCodeEquals(403);
  }

  /**
   * Tests access for 'administer backup and migrate' permission
   */
  public function testAdminister() {
    $this->drupalLogin($this->drupalCreateUser(['administer backup and migrate']));
    $this->drupalGet('admin/config/development/backup_migrate/settings');
    $this->assertSession()->statusCodeEquals(200);
  }

  /**
   * Tests access for 'perform backup' permission
   */
  public function testPerform() {
    $this->drupalLogin($this->drupalCreateUser(['perform backup']));
    $this->drupalGet('admin/config/development/backup_migrate');
    $this->assertSession()->statusCodeEquals(200);
  }

  /**
   * Tests access for 'restore from backup' permission
   */
  public function testRestore() {
    $this->drupalLogin($this->drupalCreateUser(['restore from backup']));
    $this->drupalGet('admin/config/development/backup_migrate/restore');
    $this->assertSession()->statusCodeEquals(200);
  }

  /**
   * Tests access for 'access backup files' permission
   */
  public function testAccessBackupFiles() {
    //Ensure backup_migrate folder exists
    $path = 'private://backup_migrate/';
    file_prepare_directory($path, FILE_CREATE_DIRECTORY);
    $this->drupalLogin($this->drupalCreateUser(['access backup files']));
    $this->drupalGet('admin/config/development/backup_migrate/backups');
    $this->assertSession()->statusCodeEquals(200);
  }

  /**
   * Tests access for 'delete backup files' permission
   */
  public function testDeleteBackupFiles() {
    $this->drupalLogin($this->drupalCreateUser(['delete backup files']));
    $this->drupalGet('admin/config/development/backup_migrate/settings/destination/backups/private_files/delete/none.mysql.gz');
    $this->assertSession()->statusCodeEquals(200);
  }

}
