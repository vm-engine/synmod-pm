<x-synapse-document-group-card
    :title="$title"
    :badge="$badge"
    :type="$documentType"
>
    <x-synapse-document-revision-list :documents="$this->documents" />
</x-synapse-document-group-card>
