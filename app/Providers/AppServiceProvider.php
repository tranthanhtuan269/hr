<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use Validator;
use App\Helpers\BatvHelper;
use App\Models\ConfigWeb;
use App\Models\LoanCapital;
use App\Models\ConfigLoanCapital;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
      public function boot()
      {
        Validator::extend('greater_than_field', function($attribute, $value, $parameters, $validator) {
          $min_field = $parameters[0];
          $data = $validator->getData();
          $min_value = $data[$min_field];
          $min_value = BatvHelper::formatDate( $min_value , 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          $value = BatvHelper::formatDate($value, 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          if( (strtotime($min_value) - strtotime($value))>0 ){
            return FALSE;
          }else{
            return TRUE;
          }
        });   

        Validator::replacer('greater_than_field', function($message, $attribute, $rule, $parameters) {
          return str_replace(':field', $parameters[0], $message);
        });


        Validator::extend('validator_datetime_from_to', function($attribute, $value, $parameters, $validator) {
          $min_field = $parameters[0];
          $data = $validator->getData();
          $min_value = $data[$min_field];
          $min_value = BatvHelper::formatDate( $min_value , 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          $value = BatvHelper::formatDate($value, 'd/m/Y', $formatDate='Y-m-d',$timeFormat='H:i:s',$time=false);
          return ( (BatvHelper::handlingTime($min_value) - BatvHelper::handlingTime($value))>0 ) ? FALSE : TRUE;
        });   

        Validator::replacer('validator_datetime_from_to', function($message, $attribute, $rule, $parameters) {
          return str_replace(':field', $parameters[0], $message);
        });

        // Validator::extend('check_max_hour_week', function($attribute, $value, $parameters, $validator) {
        //   $data = $validator->getData();
        //   return ( $data['max_hour_week'] < $data['min_hour_week'] ) ? FALSE : TRUE;
        // });

        Validator::extend('check_max_hour_month', function($attribute, $value, $parameters, $validator) {
          $data = $validator->getData();
          return ( $data['max_hour_month'] <= $data['min_hour_month'] ) ? FALSE : TRUE;
        });

        Validator::extend('check_max_hour_day_normal', function($attribute, $value, $parameters, $validator) {
          $data = $validator->getData();
          return ( $data['max_hour_day_normal'] <= $data['min_hour_day_normal'] ) ? FALSE : TRUE;
        });

        Validator::extend('check_max_hour_day_holiday', function($attribute, $value, $parameters, $validator) {
          $data = $validator->getData();
          return ( $data['max_hour_day_holiday'] <= $data['min_hour_day_holiday'] ) ? FALSE : TRUE;
        });

        Validator::extend('check_score_register_loan_capital', function($attribute, $value, $parameters, $validator) {
          $loan_capital = LoanCapital::where('personnel_id', Auth::user()->id)->first();
          $score = (count($loan_capital) > 0) ? $loan_capital->score : 0;
          $date_current = date('Y-m-d');
          $config_loan_capital = ConfigLoanCapital::whereDate('apply_from', '<=', $date_current)->whereDate('apply_to', '>=', $date_current)->first();
          $score_min = (count($config_loan_capital) > 0) ? $config_loan_capital->score : 0;
          return ( $score < $score_min ) ? FALSE : TRUE;
        });

        Validator::extend('validate_time', function($attribute, $value, $parameters, $validator) {
          $data = $validator->getData();
          $apply_from = BatvHelper::formatDate($data['apply_from'], 'd/m/Y', 'Y-m-d', 'H:i:s', false);
          $apply_to = BatvHelper::formatDate($data['apply_to'], 'd/m/Y', 'Y-m-d', 'H:i:s', false);
          $date_current = date('Y-m-d');
          $config_loan_capital = ConfigLoanCapital::whereBetween('apply_from', [$apply_from, $apply_to])->orWhereBetween('apply_to', [$apply_from, $apply_to])->count();
          return ($config_loan_capital > 0) ? FALSE : TRUE;
        });

        Validator::extend('validate_time_update', function($attribute, $value, $parameters, $validator) {
          $data = $validator->getData();
          // echo '<pre>';
          // print_r($data);die;
          $apply_from = BatvHelper::formatDate($data['apply_from'], 'd/m/Y', 'Y-m-d', 'H:i:s', false);
          $apply_to = BatvHelper::formatDate($data['apply_to'], 'd/m/Y', 'Y-m-d', 'H:i:s', false);
          $date_current = date('Y-m-d');
          $config_loan_capital = ConfigLoanCapital::where('id', '<>', $data['id'])
                                                  ->where(function($query) use ($apply_from, $apply_to) {
                                                      $query->whereBetween('apply_from', [$apply_from, $apply_to])->orWhereBetween('apply_to', [$apply_from, $apply_to]);
                                                  })
                                                  ->count();

          return ($config_loan_capital > 0) ? FALSE : TRUE;
        });
      }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
