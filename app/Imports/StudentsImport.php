<?php

namespace App\Imports;

use App\Models\Contact;
use App\Models\Profile;
use App\Models\Student;
use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        $rows = $rows->filter(function ($row) {
            return $row['name'] && strlen($row['telephone']) == 11;
        });
        foreach ($rows as $key => $row) {
            $profileName = $row['name'];
            $telephone = $row['telephone'];
            $password = substr($telephone, 7);
            $telephone = '+86'.$telephone;
            $grade = $row['grade'] ?: 0;
            $remark = $row['remark'] ?: 'imports';
            $sex = $row['sex'] == '女' ? 0 : 1;
            $book_id = null;
            $birthday = $row['age'] ? (date('Y') - $row['age']).'-01-01' : null; //Y-m-d 2019-2
            $recommendUid = $row['recommend_uid'] ?: null;
            //判断用户是否存在
            if (Profile::where('telephone', $telephone)->count()) {
                \Log::error($profileName.'已存在，请检查！'.$telephone, [__CLASS__, __LINE__, $row]);
                continue;
            }
            // create login user
            $name = User::getRegisterName($profileName);
            $email = $name.'_'.Str::random(6).'@student.com';

            \Log::info(__CLASS__, [$row, $name, $password, $email, $birthday, $remark, $recommendUid]);
            $userData = [
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make($password),
            ];
            $user = User::create($userData);

            $user->assignRole(User::ROLES['student']);

            $student = Student::firstOrNew([
                'user_id' => $user->id,
                'grade'   => $grade,
                'remark'  => $remark,
                'book_id' => $book_id,
            ]);
            $student->save();

            //确保只有一个手机号
            $profile = Profile::firstOrNew([
                'telephone' => $telephone,
            ]);

            $profile->fill([
                'user_id'       => $user->id,
                'name'          => $profileName,
                'sex'           => $sex,
                'birthday'      => $birthday,
                'recommend_uid' => $recommendUid,
            ])->save();

            Contact::firstOrNew([
                'profile_id' => $profile->id,
                'type'       => 1, // Contact::TYPES[1] = 'wechat/qq',
                'number'     => $telephone,
                'remark'     => 'imports',
            ])->save();
        }
    }
}
