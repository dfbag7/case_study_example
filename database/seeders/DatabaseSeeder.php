<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyField;
use App\Models\PropertyFieldValue;
use App\Models\SearchProfile;
use App\Models\SearchProfileValue;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;

class DatabaseSeeder extends Seeder
{
    use WithFaker;

    public function __construct()
    {
        $this->setUpFaker();
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DefaultValues::class,
        ]);

        $this->seedProperties();
        $this->seedSearchProfiles();
    }

    protected function generateArea()
    {
        return $this->faker->numberBetween(0, 500);
    }

    protected function generateYearOfConstruction()
    {
        return Carbon::create($this->faker->dateTimeThisCentury)->year;
    }

    protected function generateRooms()
    {
        return $this->faker->numberBetween(1, 20);
    }

    protected function generateHeatingType()
    {
        return $this->faker->randomElement(['gas', 'electricity', 'central']);
    }

    protected function generateParking()
    {
        return $this->faker->randomElement(['false', 'true']);
    }

    protected function generateReturnActual()
    {
        return $this->faker->randomFloat(2, 0, 100);
    }

    protected function generatePrice()
    {
        return $this->faker->randomFloat(0, 0, 10000000);
    }

    protected function generateFieldValue(PropertyField $field) : string
    {
        return match ($field->name) {
            'area' => $this->generateArea(),
            'yearOfConstruction' => $this->generateYearOfConstruction(),
            'rooms' => $this->generateRooms(),
            'heatingType' => $this->generateHeatingType(),
            'parking' => $this->generateParking(),
            'returnActual' => $this->generateReturnActual(),
            'price' => $this->generatePrice(),
            default => throw new \InvalidArgumentException('Wrong field name: ' . $field->name),
        };
    }

    /**
     * @param mixed $minRangeValue
     * @param mixed $maxRangeValue
     */
    public function swapValuesIfNeeded(mixed &$minRangeValue, mixed &$maxRangeValue): void
    {
        if ($minRangeValue !== null && $maxRangeValue !== null && $maxRangeValue < $minRangeValue)
        {
            $t = $minRangeValue;
            $minRangeValue = $maxRangeValue;
            $maxRangeValue = $t;
        }
    }

    protected function generateSearchProfileValue(PropertyField $field): array
    {
        $kind = $this->faker->randomElement([SearchProfileValue::DIRECT, SearchProfileValue::RANGE]);
        $directValue = null;
        $minRangeValue = null;
        $maxRangeValue = null;

        switch($field->name) {
            case 'area':
                switch($kind)
                {
                    case SearchProfileValue::DIRECT:
                        $directValue = $this->generateArea();
                        break;

                    case SearchProfileValue::RANGE:
                        $minRangeValue = $this->faker->randomElement([null, $this->generateArea()]);
                        $maxRangeValue = $this->faker->randomElement([null, $this->generateArea()]);
                        break;
                }
                break;

            case 'yearOfConstruction':
                switch($kind)
                {
                    case SearchProfileValue::DIRECT:
                        $directValue = $this->generateYearOfConstruction();
                        break;

                    case SearchProfileValue::RANGE:
                        $minRangeValue = $this->faker->randomElement([null, $this->generateYearOfConstruction()]);
                        $maxRangeValue = $this->faker->randomElement([null, $this->generateYearOfConstruction()]);
                        break;
                }
                break;

            case 'rooms':
                switch($kind)
                {
                    case SearchProfileValue::DIRECT:
                        $directValue = $this->generateRooms();
                        break;

                    case SearchProfileValue::RANGE:
                        $minRangeValue = $this->faker->randomElement([null, $this->generateRooms()]);
                        $maxRangeValue = $this->faker->randomElement([null, $this->generateRooms()]);
                        break;
                }
                break;

            case 'heatingType':
                break;

            case 'parking':
                switch($kind)
                {
                    case SearchProfileValue::DIRECT:
                        $directValue = $this->generateParking();
                        break;

                    case SearchProfileValue::RANGE:
                        $minRangeValue = $this->faker->randomElement([null, $this->generateParking()]);
                        $maxRangeValue = $this->faker->randomElement([null, $this->generateParking()]);
                        break;
                }
                break;

            case 'returnActual':
                switch($kind)
                {
                    case SearchProfileValue::DIRECT:
                        $directValue = $this->generateReturnActual();
                        break;

                    case SearchProfileValue::RANGE:
                        $minRangeValue = $this->faker->randomElement([null, $this->generateReturnActual()]);
                        $maxRangeValue = $this->faker->randomElement([null, $this->generateReturnActual()]);
                        break;
                }
                break;

            case 'price':
                switch($kind)
                {
                    case SearchProfileValue::DIRECT:
                        $directValue = $this->generatePrice();
                        break;

                    case SearchProfileValue::RANGE:
                        $minRangeValue = $this->faker->randomElement([null, $this->generatePrice()]);
                        $maxRangeValue = $this->faker->randomElement([null, $this->generatePrice()]);
                        break;
                }
                break;

            default:
                throw new \InvalidArgumentException('Wrong field name: ' . $field->name);
        }

        $this->swapValuesIfNeeded($minRangeValue, $maxRangeValue);
        return [$kind, $directValue, $minRangeValue, $maxRangeValue];
    }

    /**
     * @return void
     */
    protected function seedProperties(): void
    {
        /** @var \Illuminate\Database\Eloquent\Collection $allFields */
        $allFields = PropertyField::all()->keyBy('id');
        $numberOfPropertyValuesToGenerate = $this->faker->numberBetween(1, $allFields->count());
        $fieldIds = $this->faker->randomElements($allFields->pluck('id')->all(), $numberOfPropertyValuesToGenerate);

        Property::factory()
            ->count(10)
            ->has(PropertyFieldValue::factory()
                ->count($numberOfPropertyValuesToGenerate)
                ->sequence(function ($sequence) use ($fieldIds, $allFields, $numberOfPropertyValuesToGenerate) {
                    $id = $fieldIds[$sequence->index % $numberOfPropertyValuesToGenerate];
                    $field = $allFields->get($id);
                    return [
                        'property_field_id' => $id,
                        'value' => $this->generateFieldValue($field)
                    ];
                }))
            ->create();
    }

    /**
     * @return void
     */
    protected function seedSearchProfiles(): void
    {
        $allFields = PropertyField::all()->keyBy('id');
        $numberOfProfileValuesToGenerate = $this->faker->numberBetween(1, $allFields->count());
        $fieldIds = $this->faker->randomElements($allFields->pluck('id')->all(), $numberOfProfileValuesToGenerate);

        SearchProfile::factory()
            ->count(10)
            ->has(SearchProfileValue::factory()
                ->count($numberOfProfileValuesToGenerate)
                ->sequence(function($sequence) use($fieldIds, $allFields, $numberOfProfileValuesToGenerate) {
                    $id = $fieldIds[$sequence->index % $numberOfProfileValuesToGenerate];
                    $field = $allFields->get($id);
                    list($kind, $directValue, $minRangeValue, $maxRangeValue) = $this->generateSearchProfileValue($field);
                    return [
                        'property_field_id' => $id,
                        'kind' => $kind,
                        'direct_value' => $directValue,
                        'min_range_value' => $minRangeValue,
                        'max_range_value' => $maxRangeValue,
                    ];
                }))
            ->create();
    }
}
