<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\PropertyField;
use App\Models\PropertyFieldValue;
use App\Models\SearchProfile;
use App\Models\SearchProfileValue;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    /**
     * Creates interrelated objects (PropertyField, PropertyFieldValue, SearchProfile, SearchProfileValue, Property)
     * with proper relations between them.
     *
     * @return array
     */
    private function setupStructure(string  $propertyFieldValue, string $searchValueKind,
                                    ?string $directValue, ?string $minRangeValue, ?string $maxRangeValue): array
    {
        /** @var PropertyField $propertyField */
        $propertyField = PropertyField::factory()
            ->state([
                'name' => 'test_property'
            ])
            ->make();

        /** @var PropertyFieldValue[] $propertyFieldValues */
        $propertyFieldValues = PropertyFieldValue::factory()
            ->count(1)
            ->state([
                'value' => $propertyFieldValue,
            ])
            ->make();
        $propertyFieldValues[0]->setRelation('propertyField', $propertyField);

        /** @var Property $property */
        $property = Property::factory()->make();
        $property->setRelation('propertyFieldValues', $propertyFieldValues);


        /** @var SearchProfileValue[] $searchProfileValue */
        $searchProfileValues = SearchProfileValue::factory()
            ->count(1)
            ->state([
                'kind' => $searchValueKind,
                'direct_value' => $directValue,
                'min_range_value' => $minRangeValue,
                'max_range_value' => $maxRangeValue,
            ])
            ->make();
        $searchProfileValues[0]->setRelation('propertyField', $propertyField);

        /** @var SearchProfile $searchProfile */
        $searchProfile = SearchProfile::factory()
            ->make();
        $searchProfile->setRelation('searchProfileValues', $searchProfileValues);

        return [$property, $searchProfile];
    }

    public function test_match_direct_works()
    {
        list($property, $searchProfile) = $this->setupStructure('0.55', SearchProfileValue::DIRECT, '0.55', null, null);

        list($score, $strictMatchesCount, $looseMatchesCount) = $property->calculateMatch($searchProfile);

        $this->assertEquals(100, $score);
        $this->assertEquals(1, $strictMatchesCount);
        $this->assertEquals(0, $looseMatchesCount);
    }

    public function test_no_match_direct_works()
    {
        list($property, $searchProfile) = $this->setupStructure('0.55', SearchProfileValue::DIRECT, '10.56', null, null);

        list($score, $strictMatchesCount, $looseMatchesCount) = $property->calculateMatch($searchProfile);

        $this->assertEquals(0, $score);
        $this->assertEquals(0, $strictMatchesCount);
        $this->assertEquals(0, $looseMatchesCount);
    }

    public function test_match_range_strict_works()
    {
        list($property, $searchProfile) = $this->setupStructure('150', SearchProfileValue::RANGE, null, '100', '200');

        list($score, $strictMatchesCount, $looseMatchesCount) = $property->calculateMatch($searchProfile);

        $this->assertEquals(100, $score);
        $this->assertEquals(1, $strictMatchesCount);
        $this->assertEquals(0, $looseMatchesCount);
    }

    public function test_match_range_strict_when_min_is_null_works()
    {
        list($property, $searchProfile) = $this->setupStructure('150', SearchProfileValue::RANGE, null, null, '200');

        list($score, $strictMatchesCount, $looseMatchesCount) = $property->calculateMatch($searchProfile);

        $this->assertEquals(100, $score);
        $this->assertEquals(1, $strictMatchesCount);
        $this->assertEquals(0, $looseMatchesCount);
    }

    public function test_match_range_strict_when_max_is_null_works()
    {
        list($property, $searchProfile) = $this->setupStructure('150', SearchProfileValue::RANGE, null, 100, null);

        list($score, $strictMatchesCount, $looseMatchesCount) = $property->calculateMatch($searchProfile);

        $this->assertEquals(100, $score);
        $this->assertEquals(1, $strictMatchesCount);
        $this->assertEquals(0, $looseMatchesCount);
    }

    public function test_match_range_loose_when_below_min_works()
    {
        list($property, $searchProfile) = $this->setupStructure('90', SearchProfileValue::RANGE, null, '100', '200');

        list($score, $strictMatchesCount, $looseMatchesCount) = $property->calculateMatch($searchProfile);

        $this->assertEquals(50, $score);
        $this->assertEquals(0, $strictMatchesCount);
        $this->assertEquals(1, $looseMatchesCount);
    }

    public function test_match_range_loose_when_above_max_works()
    {
        list($property, $searchProfile) = $this->setupStructure('205', SearchProfileValue::RANGE, null, '100', '200');

        list($score, $strictMatchesCount, $looseMatchesCount) = $property->calculateMatch($searchProfile);

        $this->assertEquals(50, $score);
        $this->assertEquals(0, $strictMatchesCount);
        $this->assertEquals(1, $looseMatchesCount);
    }

    public function test_no_match_range_works()
    {
        list($property, $searchProfile) = $this->setupStructure('10', SearchProfileValue::RANGE, null, '100', '200');

        list($score, $strictMatchesCount, $looseMatchesCount) = $property->calculateMatch($searchProfile);

        $this->assertEquals(0, $score);
        $this->assertEquals(0, $strictMatchesCount);
        $this->assertEquals(0, $looseMatchesCount);
    }
}
