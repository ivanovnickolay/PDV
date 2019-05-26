<?php

namespace App\Form\search;

use App\Entity\forForm\search\docFromParam;
use App\Entity\SearchERPN;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchReestrType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $correctMonthCreate = array(
            'январь'=>'1',
            'февраль'=>'2',
            'март'=>'3',
            'апрель'=>'4',
            'май'=>'5',
            'июнь'=>'6',
            'июль'=>'7',
            'август'=>'8',
            'сентябрь'=>'9',
            'октябрь'=>'10',
            'ноябрь'=>'11',
            'декабрь'=>'12');
        $correctYearCreate=array(
            '2015'=>2015,
            '2016'=>2016,
            '2017'=>2017,
            '2018'=>2018,
            '2019'=>2019);
        $correctTypeDoc = array(
            "Налоговая накладная"=>"ПНЕ",
            "Расчет корректировки"=>"РКЕ",
            "Отельный счет"=>"ГР",
            "Транспортный билет"=>"ТК",
            "Таможенная декларация"=>"МДЕ"
        );
        $correctRoute = array(
            "Обязательства"=>"Обязательства",
            "Кредит"=>"Кредит");

        $builder
            ->add('monthCreate',ChoiceType::class, array(
                'choices' =>$correctMonthCreate ,'label'=>'Месяц создания '))
                ->add('yearCreate',ChoiceType::class, array(
                    'choices' =>$correctYearCreate,'label'=>'Год создания '))
                    ->add('numDoc',TextType::class,array('label'=>'Номер документа','required'   => false))
                        ->add('dateCreateDoc',DateType::class,array('widget' => 'single_text',
                            'label'=>'Дата создания документа ',
                            'format' => 'dd-MM-yyyy',
                            'attr' => ['type' => 'date']
                        ))
                            ->add('typeDoc',ChoiceType::class, array(
                                'choices'=>$correctTypeDoc, 'label'=>"Тип документа"
                            ))
                        ->add('iNN',IntegerType::class,array(
                            'label'=>"ИНН клиента",
                            'attr'=>['step'=>1]))
                    ->add('routeSearch', ChoiceType::class, array(
                        'choices'=>$correctRoute, 'label'=>"Направление поиска"
                    ))
                ->add("Search",SubmitType::class,array('label'=>"Искать информацию "))
            ->add("Clear",ResetType::class,array('label'=>"Сбросить "))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => docFromParam::class,
        ]);
    }
}
