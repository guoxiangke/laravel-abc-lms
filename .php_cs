<?php
// https://docs.styleci.io/presets#laravel
$fixers = [
    // '-psr0',
    "align_phpdoc",
    "binary_operator_spaces",
    "blank_line_after_namespace",
    "blank_line_after_opening_tag",
    "blank_line_before_return",
    "braces",
    "cast_spaces",
    "class_definition",
    "concat_without_spaces",
    "declare_equal_normalize",
    "elseif",
    "encoding",
    "full_opening_tag",
    "function_declaration",
    "function_typehint_space",
    "hash_to_slash_comment",
    "heredoc_to_nowdoc",
    "include",
    "indentation",
    "length_ordered_imports",
    "lowercase_cast",
    "lowercase_constants",
    "lowercase_keywords",
    "lowercase_static_reference",
    "magic_constant_casing",
    "magic_method_casing",
    "method_argument_space",
    "method_separation",
    "method_visibility_required",
    "native_function_casing",
    "native_function_type_declaration_casing",
    "no_alias_functions",
    "no_blank_lines_after_class_opening",
    "no_blank_lines_after_phpdoc",
    "no_blank_lines_after_throw",
    "no_blank_lines_between_imports",
    "no_blank_lines_between_traits",
    "no_closing_tag",
    "no_empty_phpdoc",
    "no_empty_statement",
    "no_extra_consecutive_blank_lines",
    "no_leading_import_slash",
    "no_leading_namespace_whitespace",
    "no_multiline_whitespace_around_double_arrow",
    "no_multiline_whitespace_before_semicolons",
    "no_short_bool_cast",
    "no_singleline_whitespace_before_semicolons",
    "no_spaces_after_function_name",
    "no_spaces_inside_offset",
    "no_spaces_inside_parenthesis",
    "no_trailing_comma_in_list_call",
    "no_trailing_comma_in_singleline_array",
    "no_trailing_whitespace",
    "no_trailing_whitespace_in_comment",
    "no_unneeded_control_parentheses",
    "no_unreachable_default_argument_value",
    "no_unused_imports",
    "no_useless_return",
    "no_whitespace_before_comma_in_array",
    "no_whitespace_in_blank_line",
    "normalize_index_brace",
    "not_operator_with_successor_space",
    "object_operator_without_whitespace",
    "phpdoc_indent",
    "phpdoc_inline_tag",
    "phpdoc_no_access",
    "phpdoc_no_package",
    "phpdoc_no_useless_inheritdoc",
    "phpdoc_scalar",
    "phpdoc_single_line_var_spacing",
    "phpdoc_summary",
    "phpdoc_to_comment",
    "phpdoc_trim",
    "phpdoc_type_to_var",
    "phpdoc_types",
    "phpdoc_var_without_name",
    "post_increment",
    "print_to_echo",
    "property_visibility_required",
    "psr4",
    "self_accessor",
    "short_array_syntax",
    "short_list_syntax",
    "short_scalar_cast",
    "simplified_null_return",
    "single_blank_line_at_eof",
    "single_blank_line_before_namespace",
    "single_class_element_per_statement",
    "single_import_per_statement",
    "single_line_after_imports",
    "single_quote",
    "space_after_semicolon",
    "standardize_not_equals",
    "switch_case_semicolon_to_colon",
    "switch_case_space",
    "ternary_operator_spaces",
    "trailing_comma_in_multiline_array",
    "trim_array_spaces",
    "unalign_equals",
    "unary_operator_spaces",
    "unix_line_endings",
    "whitespace_after_comma_in_array",
];

$rules = [
    '@PSR2' => true,
    'binary_operator_spaces' => ['align_double_arrow' => true],
    'linebreak_after_opening_tag' => true,
    'array_syntax' => ['syntax' => 'short'],
    'no_multiline_whitespace_before_semicolons' => true,
    'no_short_echo_tag' => true,
    // 'no_unused_imports' => true,
    'not_operator_with_successor_space' => true,
    'no_useless_else' => true,
    'ordered_imports' => [
        'sortAlgorithm' => 'length',
    ],
    // 'phpdoc_add_missing_param_annotation' => true,
    // 'phpdoc_indent' => true,
    // 'phpdoc_no_package' => true,
    // 'phpdoc_order' => true,
    // 'phpdoc_separation' => true,
    // 'phpdoc_single_line_var_spacing' => true,
    // 'phpdoc_trim' => true,
    // 'phpdoc_var_without_name' => true,
    // 'phpdoc_to_comment' => true,
    'single_quote' => true,
    'ternary_operator_spaces' => true,
    'trailing_comma_in_multiline_array' => true,
    'trim_array_spaces' => true,
];

$finder = PhpCsFixer\Finder::create()
    ->notPath('bootstrap/cache')
    ->exclude('storage')
    ->exclude('vendor')
    ->exclude('tests')
    // ->exclude('public')
    // ->exclude('resources')
    ->in(__DIR__ ."/app") 
    ->in(__DIR__ ."/database") 
    ->in(__DIR__ ."/routes") 
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
;
# https://gist.github.com/petericebear/72e2b462f59b305c551c
return PhpCsFixer\Config::create()
    ->setRules($rules)
    // ->fixers($fixers)
    ->setFinder($finder)
    ->setUsingCache(false);
;