<?php

namespace OpenSkill\Datatable\Versions;


use OpenSkill\Datatable\DatatableException;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class DTVersionEngine
 * @package OpenSkill\Datatable\Versions
 *
 * The DTVersionEngine will select the correct version based on the current request parameters. By default it will
 * support 1.9 and 1.10 of the datatable version.
 */
class VersionEngine
{
    /** @var Version The version for the request if it can be determined */
    private $version = null;

    /**
     * DTVersionEngine constructor. The first version will be set as default version.
     *
     * @param Version[] $versions an array of possible version this data table supports
     */
    public function __construct(array $versions)
    {
        $this->setVersionFromRequest($versions);
    }

    /**
     * Set the default version that will be used by the VersionEngine.
     *
     * @param Version[] $versions an array of possible version this data table supports
     */ 
    private function setDefaultVersion(array $versions)
    {
        if (count($versions) < 1) {
            return;
        }

        $this->version = $versions[0];
    }

    /**
     * Pick the verison of an engine that can parse a request.
     *
     * @param Version[] $versions an array of possible version this data table supports
     */
    private function setVersionFromRequest(array $versions)
    {
        $this->setDefaultVersion($versions);

        foreach ($versions as $v) {
            if ($v->canParseRequest()) {
                $this->version = $v;
                break;
            }
        }
    }

    /**
     * @return Version Will return the version that is currently selected to handle the request.
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return bool true if one of the versions can handle the request, false otherwise
     */
    public function hasVersion()
    {
        return $this->version != null;
    }

    /**
     * @param Version $version The version that should be used in this request.
     */
    public function setVersion(Version $version)
    {
        $this->version = $version;
    }
}
