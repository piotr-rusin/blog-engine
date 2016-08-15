<?php

/*
 * The MIT License
 *
 * Copyright 2016 Piotr Rusin <piotr.rusin88@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A class of form for creating and editing comments.
 *
 * @author Piotr Rusin <piotr.rusin88@gmail.com>
 */
class CommentType extends AbstractType
{
    /**
     * Modify the form depending on the pre-populated data.
     *
     * @param FormEvent $event
     */
    public function onPresetData(FormEvent $event)
    {
        $comment = $event->getData();
        $form = $event->getForm();
        if (!$comment || $comment->getRegisteredAuthor() === null) {
            $form
                ->add(
                    'unregisteredAuthorName',
                    TextType::class,
                    array('label' => 'form.comment.author_name')
                )
                ->add(
                    'unregisteredAuthorEmail',
                    EmailType::class,
                    array(
                        'label' => 'form.comment.author_email',
                        'required' => false,
                    )
                )
            ;
        }
    }

    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'content',
                TextareaType::class,
                array('label' => 'form.comment.content')
            )
            ->add(
                'save',
                SubmitType::class,
                array('label' => 'form.comment.submit')
            )
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                array($this, 'onPresetData')
            )
        ;
    }

    /**
     * Configure options.
     *
     * This method sets data_class and validation_groups options.
     * The validation groups are chosen based on underlying data of the form.
     *
     * @param \AppBundle\Form\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $getValidationGroups = function (FormInterface $form) {
            $comment = $form->getData();

            if ($comment && $comment->getRegisteredAuthor() !== null) {
                return array('RegisteredAuthor');
            }

            return array('unregisteredAuthor');
        };

        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Comment',
            'validation_groups' => $getValidationGroups,
        ));
    }
}
