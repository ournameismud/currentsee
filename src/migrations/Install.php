<?php
/**
 * Current See plugin for Craft CMS 3.x
 *
 * Plugin to generate externally accessible feed of versions
 *
 * @link      https://ournameismud.co.uk/
 * @copyright Copyright (c) 2020 @cole007
 */

namespace ournameismud\currentsee\migrations;

use ournameismud\currentsee\CurrentSee;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * @author    @cole007
 * @package   CurrentSee
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->createIndexes();
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
            $this->insertDefaultData();
        }

        return true;
    }

   /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%currentsee_plugin}}');
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                '{{%currentsee_plugin}}',
                [
                    'id' => $this->primaryKey(),
                    'namespace' => $this->string(255)->notNull()->defaultValue(''),
                    'current' => $this->string(255)->notNull()->defaultValue(''),
                    'latest' => $this->string(255)->notNull()->defaultValue(''),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid' => $this->uid()
                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * @return void
     */
    protected function createIndexes()
    {

    }

    /**
     * @return void
     */
    protected function addForeignKeys()
    {
        // $this->addForeignKey(
        //     $this->db->getForeignKeyName('{{%currentsee_plugin}}', 'siteId'),
        //     '{{%currentsee_plugin}}',
        //     '{{%sites}}',
        //     'id',
        //     'CASCADE',
        //     'CASCADE'
        // );
    }

    /**
     * @return void
     */
    protected function insertDefaultData()
    {
    }

    /**
     * @return void
     */
    protected function removeTables()
    {
        $this->dropTableIfExists('{{%currentsee_plugin}}');
    }
}
