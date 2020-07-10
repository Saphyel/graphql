<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\ConnectionInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;

use function count;

class AuthorResolver implements ResolverInterface
{
    private AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * @param ResolveInfo $info
     * @param mixed $value
     * @param ArgumentInterface $args
     * @return mixed
     */
    public function __invoke(ResolveInfo $info, $value, ArgumentInterface $args)
    {
        $method = $info->fieldName;

        return $this->$method($value, $args);
    }

    public function resolve(int $id): ?Author
    {
        return $this->authorRepository->findOneBy(['id' => $id]);
    }

    public function id(Author $author): int
    {
        return $author->getId();
    }

    public function email(Author $author): string
    {
        return $author->getEmail();
    }

    public function posts(Author $author, ArgumentInterface $args): ConnectionInterface
    {
        $posts = $author->getPosts();
        $paginator = new Paginator(
            static function ($offset, $limit) use ($posts) {
                return $posts->slice($offset, $limit ?? 10);
            }
        );

        return $paginator->auto($args, count($posts));
    }
}
