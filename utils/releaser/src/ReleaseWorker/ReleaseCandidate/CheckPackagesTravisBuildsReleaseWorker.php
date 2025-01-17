<?php

declare(strict_types=1);

namespace Shopsys\Releaser\ReleaseWorker\ReleaseCandidate;

use Shopsys\Releaser\ReleaseWorker\AbstractCheckPackagesTravisBuildsReleaseWorker;
use Shopsys\Releaser\Stage;

final class CheckPackagesTravisBuildsReleaseWorker extends AbstractCheckPackagesTravisBuildsReleaseWorker
{
    /**
     * @return string
     */
    public function getStage(): string
    {
        return Stage::RELEASE_CANDIDATE;
    }

    /**
     * @return string
     */
    protected function getBranchName(): string
    {
        return $this->initialBranchName;
    }
}
