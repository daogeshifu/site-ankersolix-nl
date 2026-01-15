<?php

return [
    'title' => '常见问题',
    'subtitle' => '搜索我们的常见问题解答，找到您想要的答案。',

    'sections' => [
        'detection' => [
            'title' => 'AI 检测',
            'questions' => [
                'what_is_ai_detection' => [
                    'question' => '什么是 AI 内容检测？',
                    'answer' => 'AI 内容检测是识别文本是否由人工智能工具（如 ChatGPT、GPT-4、Claude 或其他语言模型）生成的过程。我们的先进算法分析写作模式、语言标记和文体特征，以确定内容是由 AI 生成还是人工撰写的。'
                ],
                'how_accurate' => [
                    'question' => '你们的 AI 检测准确率如何？',
                    'answer' => '我们的 AI 检测系统在各种内容类型中保持超过 98% 的准确率。我们使用在数百万样本上训练的先进机器学习模型来识别 AI 生成的内容。但是，准确性可能会因内容长度、质量和用于生成它的 AI 模型而有所不同。'
                ],
                'supported_languages' => [
                    'question' => 'AI 检测器支持哪些语言？',
                    'answer' => '我们的 AI 检测器目前支持英语、西班牙语、法语、德语、中文、日语和许多其他主要语言。我们正在不断扩展语言支持以服务全球受众。'
                ],
                'detection_time' => [
                    'question' => '检测 AI 内容需要多长时间？',
                    'answer' => '检测几乎是即时的。大多数扫描在 1-3 秒内完成，即使对于较长的文档也是如此。我们先进的基础设施确保快速处理而不影响准确性。'
                ],
                'can_detect_all_ai' => [
                    'question' => '你们能检测所有 AI 模型生成的内容吗？',
                    'answer' => '是的，我们的系统可以检测由 ChatGPT（GPT-3.5、GPT-4）、Claude、Bard、Jasper、Copy.ai 和大多数其他流行的 AI 写作工具生成的内容。我们不断更新检测算法以识别新出现的 AI 模型。'
                ],
                'false_positives' => [
                    'question' => '误报怎么办？',
                    'answer' => '虽然我们努力实现最大准确性，但偶尔会出现误报，特别是对于高度结构化或公式化的人工写作。我们提供详细的置信度分数和突出显示的部分，帮助您做出明智的决定。'
                ]
            ]
        ],

        'humanization' => [
            'title' => 'AI 降重与改写',
            'questions' => [
                'what_is_humanization' => [
                    'question' => '什么是 AI 内容人性化？',
                    'answer' => 'AI 内容人性化是将 AI 生成的文本转换为听起来更自然、真实和像人类的过程。我们的工具在保留含义的同时重写内容，添加自然变化、个人风格和表征人类写作的风格元素。'
                ],
                'how_does_rewriting_work' => [
                    'question' => 'AI 改写过程如何工作？',
                    'answer' => '我们的改写引擎分析您的内容并重组句子，变化词汇，调整语气，并添加使文本看起来像人类撰写的自然缺陷。它保持核心信息，同时使内容无法被 AI 检测工具检测到。'
                ],
                'preserve_meaning' => [
                    'question' => '改写会改变我的内容含义吗？',
                    'answer' => '不会。我们的先进算法旨在保留内容的原始含义、关键事实和意图。我们只修改写作风格、句子结构和用词选择，使其听起来更自然和人性化。'
                ],
                'quality_after_rewrite' => [
                    'question' => '改写后质量会保持高水平吗？',
                    'answer' => '绝对会。我们的人性化过程实际上通过使内容更具吸引力、自然和可读性来提高内容质量。改写后的内容保持语法正确性，同时添加增强整体质量的人类风格。'
                ],
                'rewriting_time' => [
                    'question' => '改写过程需要多长时间？',
                    'answer' => '改写通常需要 5-15 秒，具体取决于内容长度。较短的文本（500 字以下）大约需要 5 秒处理，而较长的文档可能需要 30 秒。'
                ],
                'plagiarism_check' => [
                    'question' => '改写后的内容是否无抄袭？',
                    'answer' => '是的。我们的改写过程创建完全原创的独特内容。改写后的文本将通过抄袭检查，同时保持原始内容的核心思想和信息。'
                ]
            ]
        ],

        'usage' => [
            'title' => '使用与功能',
            'questions' => [
                'word_limits' => [
                    'question' => '每个套餐的字数限制是多少？',
                    'answer' => 'Essential 套餐：15万字/月，Professional 套餐：50万字/月，Premium 套餐：30万字/月。未使用的字数不会滚动到下个月。'
                ],
                'file_upload' => [
                    'question' => '我可以上传文件进行扫描吗？',
                    'answer' => '可以！Professional 和 Premium 套餐支持批量文件上传。您可以上传 PDF、DOCX、TXT 和其他常见文档格式。Essential 套餐仅支持文本粘贴。'
                ],
                'batch_processing' => [
                    'question' => '我可以一次扫描多个文档吗？',
                    'answer' => 'Professional 套餐允许同时扫描最多 250 个文件。Premium 套餐支持一次最多 50 个文件。Essential 套餐一次处理一个文档。'
                ],
                'api_access' => [
                    'question' => '你们提供 API 访问吗？',
                    'answer' => '是的，Professional 和 Premium 套餐可以使用 API 访问。我们的 RESTful API 允许与您的应用程序、CMS 或工作流程无缝集成。我们提供多种语言的综合文档和代码示例。'
                ],
                'data_privacy' => [
                    'question' => '你们如何处理我的数据和隐私？',
                    'answer' => '我们非常重视数据隐私。您的内容在传输和处理期间会被加密。我们不会永久存储您的内容，处理后会自动删除。我们从不与第三方共享您的数据。'
                ],
                'reports_history' => [
                    'question' => '我可以访问扫描历史和报告吗？',
                    'answer' => '可以。所有套餐都包括可下载报告的扫描历史。您可以将结果导出为 PDF 或 CSV 文件。Professional 和 Premium 套餐保留 12 个月的历史记录；Essential 套餐保留 3 个月。'
                ]
            ]
        ],

        'billing' => [
            'title' => '计费与支付',
            'questions' => [
                'payment_methods' => [
                    'question' => '你们接受哪些支付方式？',
                    'answer' => '我们接受所有主要信用卡（Visa、MasterCard、American Express）通过 Stripe、PayPal 和支付宝。所有交易都是安全和加密的。'
                ],
                'cancel_anytime' => [
                    'question' => '我可以随时取消订阅吗？',
                    'answer' => '是的，您可以随时取消订阅。没有取消费用。您的访问权限将持续到当前计费周期结束。'
                ],
                'refund_policy' => [
                    'question' => '你们的退款政策是什么？',
                    'answer' => '我们为所有套餐提供 7 天退款保证。如果您在前 7 天内对我们的服务不满意，请联系我们获得全额退款，无需任何理由。'
                ],
                'upgrade_downgrade' => [
                    'question' => '我可以升级或降级我的套餐吗？',
                    'answer' => '可以。您可以随时升级或降级。升级时，您将按比例收费。降级时，更改将在下一个计费周期生效。'
                ],
                'annual_savings' => [
                    'question' => '年度计费可以节省多少？',
                    'answer' => '与月度计费相比，年度套餐可节省高达 45%。例如，Professional 套餐年付每月 $24.99，而月付则为每月 $45.99。'
                ],
                'invoice_receipt' => [
                    'question' => '我会收到发票和收据吗？',
                    'answer' => '会的。每次付款后您都会立即收到电子邮件收据。您还可以随时从账户仪表板下载发票。'
                ]
            ]
        ],

        'support' => [
            'title' => '支持与帮助',
            'questions' => [
                'customer_support' => [
                    'question' => '我如何联系客户支持？',
                    'answer' => '您可以通过 support@aigcchecker.com 发送电子邮件、通过我们网站上的在线聊天（Premium 用户 24/7 可用，其他用户工作时间可用）或通过仪表板提交工单来联系我们的支持团队。'
                ],
                'response_time' => [
                    'question' => '典型的支持响应时间是多少？',
                    'answer' => 'Essential 套餐：48 小时内。Professional 套餐：24 小时内。Premium 套餐：优先支持，4 小时内响应。在线聊天可提供实时帮助。'
                ],
                'technical_issues' => [
                    'question' => '如果遇到技术问题怎么办？',
                    'answer' => '如果您遇到任何技术问题，请立即联系我们的支持团队。我们将尽快解决问题，如果停机影响您的服务，我们会延长您的订阅。'
                ],
                'training_resources' => [
                    'question' => '你们提供培训或教程吗？',
                    'answer' => '是的！我们提供全面的文档、视频教程和网络研讨会。Professional 和 Premium 用户还可以访问个性化的入职培训和高级培训材料。'
                ],
                'feature_requests' => [
                    'question' => '我可以请求新功能吗？',
                    'answer' => '当然可以！我们积极倾听用户意见。通过仪表板提交功能请求或给我们发送电子邮件。我们定期审查建议并根据用户需求确定优先级。'
                ],
                'enterprise_support' => [
                    'question' => '你们提供企业支持吗？',
                    'answer' => '是的。企业客户可获得专属客户经理、定制 SLA、优先支持和定制解决方案。请联系我们的销售团队 enterprise@aigcchecker.com 了解更多信息。'
                ]
            ]
        ]
    ],

    'contact' => [
        'title' => '还有其他问题？',
        'description' => '找不到您要找的答案？请与我们友好的团队联系。',
        'button' => '联系我们'
    ]
];
