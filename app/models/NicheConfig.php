<?php
class NicheConfig
{
    private $niches = [
        'translation' => [
            'name' => 'Translation Services',
            'icon' => '🌐',
            'fields' => [
                'source_language' => [
                    'type' => 'select',
                    'label' => 'Source Language',
                    'required' => true,
                    'options' => ['English', 'Arabic', 'French', 'Spanish', 'German']
                ],
                'target_language' => [
                    'type' => 'select',
                    'label' => 'Target Language',
                    'required' => true,
                    'options' => ['Arabic', 'English', 'French', 'Spanish', 'German']
                ],
                'word_count' => [
                    'type' => 'number',
                    'label' => 'Word Count',
                    'required' => true,
                    'placeholder' => 'e.g., 5000'
                ]
            ]
        ],
        'data_science' => [
            'name' => 'Data Science & AI',
            'icon' => '🤖',
            'fields' => [
                'data_stack' => [
                    'type' => 'multiselect',
                    'label' => 'Data Stack',
                    'required' => true,
                    'options' => ['Python', 'Pandas', 'TensorFlow', 'PyTorch', 'SQL']
                ],
                'algorithm_type' => [
                    'type' => 'select',
                    'label' => 'Algorithm Type',
                    'required' => true,
                    'options' => ['Classification', 'Regression', 'Clustering', 'NLP']
                ]
            ]
        ],
        'web_development' => [
            'name' => 'Web Development',
            'icon' => '💻',
            'fields' => [
                'frontend' => [
                    'type' => 'multiselect',
                    'label' => 'Frontend Technologies',
                    'required' => true,
                    'options' => ['React', 'Vue', 'Angular', 'HTML/CSS', 'JavaScript']
                ],
                'backend' => [
                    'type' => 'multiselect',
                    'label' => 'Backend Technologies',
                    'required' => true,
                    'options' => ['Node.js', 'PHP', 'Python', 'Java', '.NET']
                ]
            ]
        ],
        'graphic_design' => [
            'name' => 'Graphic Design',
            'icon' => '🎨',
            'fields' => [
                'software' => [
                    'type' => 'multiselect',
                    'label' => 'Required Software',
                    'required' => true,
                    'options' => ['Photoshop', 'Illustrator', 'Figma', 'InDesign', 'After Effects']
                ],
                'file_format' => [
                    'type' => 'select',
                    'label' => 'Delivery Format',
                    'required' => true,
                    'options' => ['PSD', 'AI', 'PDF', 'PNG', 'SVG', 'Figma']
                ]
            ]
        ]
    ];

    public function getAllNiches()
    {
        $result = [];
        foreach ($this->niches as $key => $niche) {
            $result[$key] = [
                'name' => $niche['name'],
                'icon' => $niche['icon']
            ];
        }
        return $result;
    }

    public function getFieldsByNiche($nicheKey)
    {
        return $this->niches[$nicheKey]['fields'] ?? [];
    }

    public function getNicheName($nicheKey)
    {
        return $this->niches[$nicheKey]['name'] ?? ucfirst($nicheKey);
    }
}