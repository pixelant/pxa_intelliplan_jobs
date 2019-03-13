<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Hooks;

use DmitryDulepov\Realurl\Cache\CacheFactory;
use DmitryDulepov\Realurl\Cache\CacheInterface;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Class DataHandler
 * @package Pixelant\PxaIntelliplanJobs\Hooks
 */
class DataHandler
{
    /**
     * @var DatabaseConnection
     */
    protected $databaseConnection = null;

    /**
     * @var CacheInterface
     */
    protected $cache = null;

    /**
     * Clears path and URL caches if the page was deleted.
     *
     * @param string $table
     * @param string|int $id
     */
    public function processCmdmap_deleteAction($table, $id)
    {
        if (ExtensionManagementUtility::isLoaded('realurl')
            && $table === 'tx_pxaintelliplanjobs_domain_model_job'
            && MathUtility::canBeInterpretedAsInteger($id)
        ) {
            $this->init();
            $this->clearUrlCacheForAliasChanges($table, $id);
        }
    }

    /**
     * Init realurl cache and db connection
     */
    protected function init()
    {
        $this->databaseConnection = $GLOBALS['TYPO3_DB'];
        $this->cache = CacheFactory::getCache();
    }
    
    /**
     * Clears URL cache if it is found in the alias table.
     *
     * @param string $tableName
     * @param int $recordId
     * @return void
     */
    protected function clearUrlCacheForAliasChanges($tableName, $recordId)
    {
        $result = $this->databaseConnection->sql_query(
            'SELECT uid,expire,url_cache_id FROM ' .
            'tx_realurl_uniqalias LEFT JOIN tx_realurl_uniqalias_cache_map ON uid=alias_uid ' .
            'WHERE tablename=' . $this->databaseConnection->fullQuoteStr($tableName, 'tx_realurl_uniqalias') . ' ' .
            'AND value_id=' . $recordId
        );
        while (false !== ($data = $this->databaseConnection->sql_fetch_assoc($result))) {
            if ($data['url_cache_id']) {
                $this->cache->clearUrlCacheById($data['url_cache_id']);
            }
            $this->databaseConnection->exec_DELETEquery(
                'tx_realurl_uniqalias',
                'uid=' . (int)$data['uid']
            );
        }
        $this->databaseConnection->sql_free_result($result);
    }
}
