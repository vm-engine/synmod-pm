<x-synapse-document-group-card
    :title="$title"
    :badge="$badge"
    :type="$documentType"
>
    <x-synapse-document-multiple-files-list :documents="$this->documents" />
</x-synapse-document-group-card>
