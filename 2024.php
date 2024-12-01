// update json field in laravel:
->update(['prefrences->theme'=>'dark']);
// get json field in laravel:
$contacts->where('params->vendor_profile', '!=', null);


-------------
img{ aspect-ratio:16/9 or 4/4 }
-------------
 public static function firstIdOfMyPeopleTeam()
    {
        $myTeamId = auth()->user()->struct->team_id ?? null;

        // Use a recursive CTE to find the top-level team
        return PeopleTeam::withRecursiveExpression(
            'team_hierarchy',
            PeopleTeam::where('id', $myTeamId)
                ->union(
                    PeopleTeam::whereColumn('id', 'team_hierarchy.parent_id')
                ),
            ['id', 'parent_id'], // Specify columns as an array
            null,
            'parent_id'
        )
            ->where('parent_id', 0)
            ->first();
    }
//مثال: اگر شما در یک سازمان هستید و می‌خواهید بفهمید بالاترین تیم بالادستی شما کدام است، از این کد استفاده می‌کنید.
-----------
