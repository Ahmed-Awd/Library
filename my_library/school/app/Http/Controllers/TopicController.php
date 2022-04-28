<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;
use App\Models\Topic;
use App\Repositories\TopicRepositoryInterface;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TopicController extends Controller
{


    private TopicRepositoryInterface $topicRepository;

    public function __construct(TopicRepositoryInterface $topicRepository)
    {
        $this->authorizeResource(Topic::class, 'topic');
        $this->topicRepository = $topicRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $withSubTopics = $request->get('withSubTopics',false);
        $topics = $this->topicRepository->get($withSubTopics);
        return response()->json($topics);
    }


    public function store(StoreTopicRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->topicRepository->store($validated);
        return response()->json(["message" => "topic created successfully"]);
    }


    public function show(Topic $topic): JsonResponse
    {
        $topic = $this->topicRepository->show($topic);
        return response()->json($topic);
    }

    public function getSubTopics(Topic $topic): JsonResponse
    {
        $subTopics = $this->topicRepository->getSubTopics($topic);
        return response()->json($subTopics);
    }

    public function getParentTopic(Topic $topic): JsonResponse
    {
        $parent = $this->topicRepository->getParentTopic($topic);
        return response()->json($parent);
    }

    public function getTopicContent(Topic $topic): JsonResponse
    {
        $contents = $this->topicRepository->getContents($topic);
        return response()->json($contents);
    }

    public function update(UpdateTopicRequest $request, Topic $topic): JsonResponse
    {
        $validated = $request->validated();
        $this->topicRepository->update($topic,$validated);
        return response()->json(["message" => "topic updated successfully"]);
    }


    public function destroy(Topic $topic): JsonResponse
    {
        $this->topicRepository->delete($topic);
        return response()->json(["message" => "topic deleted successfully"]);
    }
}
