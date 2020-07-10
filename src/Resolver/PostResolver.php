<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Entity\Author;
use App\Entity\Post;
use App\Repository\PostRepository;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class PostResolver implements ResolverInterface
{
    protected PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function __invoke(ResolveInfo $info, $value, ArgumentInterface $args)
    {
        $method = $info->fieldName;

        return $this->$method($value, $args);
    }

    public function resolve(int $id): ?Post
    {
        return $this->postRepository->findOneBy(['id' => $id]);
    }

    public function title(Post $post): string
    {
        return $post->getTitle();
    }

    public function author(Post $post): Author
    {
        return $post->getAuthor();
    }
}
