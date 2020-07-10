<?php

declare(strict_types=1);

namespace App\Mutation;

use App\Entity\Author;
use App\Entity\Post;
use App\Model\AuthorInput;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function count;

class AuthorMutation implements MutationInterface
{
    protected EntityManagerInterface $entityManager;

    protected ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function createAuthor(ArgumentInterface $args): Author
    {
        $rawArgs = $args->getArrayCopy();

        $input = new AuthorInput();
        foreach ($rawArgs['input'] as $key => $value) {
            $input->$key = $value;
        }

        $errors = $this->validator->validate($input);

        if (0 !== count($errors)) {
        }

        $author = new Author();
        $author->setEmail($input->email);

        if (!empty($input->posts)) {
            foreach ($input->posts as $title) {
                $post = new Post();
                $post->setTitle($title);
                $post->setAuthor($author);
                $author->addPost($post);
            }
        }

        $this->entityManager->persist($author);
        $this->entityManager->flush();

        return $author;
    }
}
