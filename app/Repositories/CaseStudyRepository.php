<?php

namespace App\Repositories;

use App\Modules\CaseStudy\Models\CaseStudy;
use DB;

class CaseStudyRepository
{
    public function __construct()
    {
    }

    public function getRandomCaseStudies($count=2)
    {
        return CaseStudy::inRandomOrder()->limit($count)->get();
    }

    public function getCaseStudy($slug="")
    {
        $title = "Singaporean Collection Case Study";
        $highlightTitle = "Singaporean Collection Highlights";

        if ($slug == 'singapore') {
            $title = "Singaporean Collection Case Study";
            $highlightTitle = "Singaporean Collection Highlights";
            $highlightItems = $this->highlightCaseStudySingapore();
            $resultRates = $this->resultRateSingapore();
        }

        if ($slug == 'qkl') {
            $title = "QKL Case Study";
            $highlightTitle = "QKL Highlights";
            $highlightItems = $this->highlightCaseStudyQkl();
            $resultRates = $this->resultRateQkl();
        }

        if ($slug == 'everton') {
            $title = "26 Everton Road Case Study";
            $highlightTitle = "26 Everton Road Highlights";
            $highlightItems = $this->highlightCaseStudyEverton();
            $resultRates = $this->resultRateEverton();
        }
    }

    private function getCaseStudyHighlight($slug="")
    {
        if ($slug == 'singapore') {
        }

        if ($slug == 'qkl') {
        }

        if ($slug == 'everton') {
        }
    }

    private function getCaseStudyResultRate($slug="")
    {
    }
}
