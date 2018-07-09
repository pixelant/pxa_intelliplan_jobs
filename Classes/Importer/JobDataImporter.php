<?php
declare(strict_types=1);

namespace Pixelant\PxaIntelliplanJobs\Importer;

use Pixelant\PxaIntelliplanJobs\Provider\IntelliplanDataProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

/**
 * Class JobDataImporter
 * @package Pixelant\PxaIntelliplanJobs\Importer
 */
class JobDataImporter extends AbstractImporter
{
    /**
     * While checking job list, save categories here in order to import it later
     *
     * @var array
     */
    protected $categories = [];

    protected $importFields = [
        'title',
        'pubdate',
        'description',
        'category',
        'id',
        'numberOfPositionsToFill',
        'type',
        'jobPositionTitle',
        'jobPositionTitleId',
        'jobPositionCategoryId',
        'jobLocation',
        'jobLocationId',
        'jobOccupation',
        'jobOccupationId',
        'jobCategory',
        'jobCategoryId',
        'service',
        'serviceCategory',
        'country',
        'countryId',
        'state',
        'stateId',
        'municipality',
        'municipalityId',
        'company',
        'companyLogoUrl',
        'employmentExtent',
        'employmentExtentId',
        'employmentType',
        'employmentTypeId',
        'jobLevel',
        'jobLevelId',
        'contact1name',
        'contact1email',
        'pubDateTo',
        'lastUpdated'
    ];

    /**
     * Import jobs data
     *
     * @param array $data
     * @param int $pid
     */
    public function import(array $data, int $pid)
    {
        $dataToTypo3 = [];
        foreach ($data as $jobData) {
            if ($job = $this->jobRepository->findById((int)$jobData['id'])) {

            } else {
                $newId = StringUtility::getUniqueId('NEW');

                foreach ($this->importFields as $importField) {
                    switch ($importField) {
                        // Date fields
                        case 'pubDateTo':
                        case 'lastUpdated':
                        case 'pubdate':
                            $value = 'date';
                            break;
                        default:
                            $value = $jobData[strtolower($importField)];
                    }

                    if (!empty($value)) {
                        $dataToTypo3[$newId][GeneralUtility::camelCaseToLowerCaseUnderscored($importField)] = $value;
                    }
                }
            }
        }

        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($dataToTypo3,'$dataToTypo3',16);
        die;
    }

    /**
     * Type of job importer
     *
     * @return int
     */
    public function getImporterType(): int
    {
        return IntelliplanDataProvider::JOBS_IMPORTER;
    }
}
