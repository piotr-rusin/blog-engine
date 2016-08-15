<?php

namespace AppBundle\Form;

use AppBundle\Entity\Article;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use AppBundle\Form\CommentType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Tests for \AppBundle\Entity\CommentType class.
 */
class CommentTypeTest extends TypeTestCase
{
    /**
     * Data of an unregistered author of a comment, to be used during testing.
     *
     * @var array
     */
    protected $unregisteredAuthorData = array(
            'unregisteredAuthorName' => 'John Doe',
            'unregisteredAuthorEmail' => 'john.doe@domain.com',
        );

    /**
     * Data provider method for the tests.
     *
     * @return array
     */
    public function getFormData()
    {
        return array(
            array(
                array('content' => 'A comment by a registered author.'),
            ),
            array(
                array_merge(
                    array(
                        'content' => 'A comment by an unregistered author.',
                    ),
                    $this->unregisteredAuthorData
                ),
            ),
        );
    }

    /**
     * Test form synchronization.
     *
     * @dataProvider getFormData
     *
     * @param array $formData Data entered to the form fields.
     */
    public function testIsSynchronized(array $formData)
    {
        $form = $this->getForm($formData);
        $this->assertTrue($form->isSynchronized());
    }

    /**
     * Test if expected data were submitted.
     *
     * @dataProvider getFormData
     *
     * @param array $formData Data entered to the form fields.
     */
    public function testSubmitValidData(array $formData)
    {
        $actualComment = $this->getActualComment($formData);
        $form = $this->getForm($formData, $actualComment);
        $expectedComment = $this->getExpectedComment($formData, $actualComment);

        $this->assertEquals($expectedComment, $form->getData());
    }

    /**
     * Test if the form view displays expected fields and does not display
     * fields that should not be displayed.
     *
     * @dataProvider getFormData
     *
     * @param array $formData Data entered to the form fields.
     */
    public function testFormView(array $formData)
    {
        $form = $this->getForm($formData, null, false);
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }

        $unexpectedKeys = array_diff(
            array_keys($this->unregisteredAuthorData),
            array_keys($formData)
        );

        foreach ($unexpectedKeys as $key) {
            $this->assertArrayNotHasKey($key, $children);
        }
    }

    /**
     * Return an instance of the Comment class to be used by the form.
     *
     * @param array $formData Data entered to the form fields. This value
     * may inlcude data expected to be entered by an unregistered user, in
     * which case the instance of the Comment used when creating form is
     * expected not to have a registered user associated with it.
     * @return \AppBundle\Entity\Comment
     */
    protected function getActualComment(array $formData)
    {
        $articleAuthor = new User();
        $article = new Article($articleAuthor);

        $unregisteredAuthorData = array_intersect_assoc(
            $formData,
            $this->unregisteredAuthorData
        );
        $commentAuthor = $unregisteredAuthorData ? null : new User();

        return new Comment($article, $commentAuthor);
    }

    /**
     * Get instance of the form class to be tested.
     *
     * @param array $formData Data entered to the form fields.
     * @param \AppBundle\Entity\Comment $actualComment A comment associated
     * with the form.
     * @param bool $submit If true, the form will be submitted before being
     * returned.
     * @return \AppBundle\Form\CommentType An instance of the form class
     */
    protected function getForm(
        array $formData,
        Comment $actualComment = null,
        $submit = true
    ) {
        if ($actualComment === null) {
            $actualComment = $this->getActualComment($formData);
        }

        $form = $this->factory->create(CommentType::class, $actualComment);

        if ($submit) {
            $form->submit($formData);
        }

        return $form;
    }

    /**
     * Get expected comment based on data of the actual comment and the
     * content entered into the form.
     *
     * @param array $formData Data entered to the form fields.
     * @param \AppBundle\Entity\Comment $actualComment A comment associated
     * with the form.
     * @return \AppBundle\Entity\Comment
     */
    protected function getExpectedComment(
        array $formData,
        Comment $actualComment
    ) {
        $expectedComment = new Comment(
            $actualComment->getArticle(),
            $actualComment->getRegisteredAuthor(),
            $actualComment->getUnregisteredAuthorName(),
            $actualComment->getUnregisteredAuthorEmail()
        );
        $expectedComment->setContent($formData['content']);

        return $expectedComment;
    }
}
