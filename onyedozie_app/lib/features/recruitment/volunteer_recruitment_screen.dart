import 'dart:io';
import 'package:dio/dio.dart' as dio;
import 'package:image_picker/image_picker.dart';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/providers.dart';

class VolunteerRecruitmentScreen extends ConsumerStatefulWidget {
  const VolunteerRecruitmentScreen({super.key});

  @override
  ConsumerState<VolunteerRecruitmentScreen> createState() =>
      _VolunteerRecruitmentScreenState();
}

class _VolunteerRecruitmentScreenState
    extends ConsumerState<VolunteerRecruitmentScreen>
    with SingleTickerProviderStateMixin {
  final _formKey = GlobalKey<FormState>();

  // Controllers
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _occupationController = TextEditingController();
  final _notesController = TextEditingController();

  // Geographic cascade
  List<dynamic> _lgas = [];
  List<dynamic> _wards = [];
  List<dynamic> _pollingUnits = [];
  int? _selectedLgaId;
  int? _selectedWardId;
  int? _selectedPuId;

  // Form state
  bool _isLoadingGeo = false;
  bool _isSubmitting = false;
  bool _showSuccess = false;

  // Image upload state
  File? _passportImage;
  final _picker = ImagePicker();

  Future<void> _pickImage(ImageSource source) async {
    try {
      final pickedFile = await _picker.pickImage(
        source: source,
        imageQuality: 80,
        maxWidth: 1000,
      );
      if (pickedFile != null) {
        setState(() {
          _passportImage = File(pickedFile.path);
        });
      }
    } catch (e) {
      _showSnack('Error picking image: $e', Colors.red);
    }
  }

  void _removeImage() {
    setState(() {
      _passportImage = null;
    });
  }

  void _showImageSourceOptions(BuildContext context) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      builder: (context) => Container(
        decoration: const BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
        ),
        padding: const EdgeInsets.all(20),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            const Text(
              'Select Image Source',
              textAlign: TextAlign.center,
              style: TextStyle(
                fontFamily: 'Inter',
                fontSize: 16,
                fontWeight: FontWeight.bold,
                color: Color(0xFF1E293B),
              ),
            ),
            const SizedBox(height: 20),
            ListTile(
              leading: Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: const Color(0xFF3451DB).withOpacity(0.1),
                  shape: BoxShape.circle,
                ),
                child: const Icon(Icons.camera_alt_rounded, color: Color(0xFF3451DB)),
              ),
              title: const Text('Camera', style: TextStyle(fontFamily: 'Inter', fontWeight: FontWeight.bold)),
              onTap: () {
                Navigator.pop(context);
                _pickImage(ImageSource.camera);
              },
            ),
            const Divider(),
            ListTile(
              leading: Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: const Color(0xFF3451DB).withOpacity(0.1),
                  shape: BoxShape.circle,
                ),
                child: const Icon(Icons.photo_library_rounded, color: Color(0xFF3451DB)),
              ),
              title: const Text('Gallery', style: TextStyle(fontFamily: 'Inter', fontWeight: FontWeight.bold)),
              onTap: () {
                Navigator.pop(context);
                _pickImage(ImageSource.gallery);
              },
            ),
            const SizedBox(height: 10),
          ],
        ),
      ),
    );
  }

  // Animation
  late AnimationController _successAnimController;
  late Animation<double> _scaleAnim;
  late Animation<double> _fadeAnim;

  @override
  void initState() {
    super.initState();
    _loadLgas();

    _successAnimController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 800),
    );
    _scaleAnim = CurvedAnimation(
      parent: _successAnimController,
      curve: Curves.elasticOut,
    );
    _fadeAnim = CurvedAnimation(
      parent: _successAnimController,
      curve: Curves.easeIn,
    );
  }

  @override
  void dispose() {
    _nameController.dispose();
    _phoneController.dispose();
    _occupationController.dispose();
    _notesController.dispose();
    _successAnimController.dispose();
    super.dispose();
  }

  // ─── Data Loading ─────────────────────────────────────────────

  Future<void> _loadLgas() async {
    setState(() => _isLoadingGeo = true);
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/geography/lgas');
      debugPrint('[LGA] Response type: ${response.data.runtimeType}');
      debugPrint('[LGA] Response data: ${response.data}');
      final data = response.data is Map ? response.data : <String, dynamic>{};
      if (data['success'] == true && data['data'] is List) {
        setState(() => _lgas = data['data']);
        debugPrint('[LGA] Loaded ${_lgas.length} LGAs');
        if (_lgas.isNotEmpty) {
          debugPrint('[LGA] First item id type: ${_lgas.first['id'].runtimeType}, value: ${_lgas.first['id']}');
        }
      } else {
        debugPrint('[LGA] Unexpected response: success=${data['success']}, data type=${data['data']?.runtimeType}');
      }
    } catch (e) {
      debugPrint('[LGA] Error loading LGAs: $e');
    }
    finally {
      if (mounted) setState(() => _isLoadingGeo = false);
    }
  }

  Future<void> _loadWards(int lgaId) async {
    setState(() {
      _isLoadingGeo = true;
      _wards = [];
      _pollingUnits = [];
      _selectedWardId = null;
      _selectedPuId = null;
    });
    try {
      final client = ref.read(apiClientProvider);
      final response = await client.get('/geography/wards?lga_id=$lgaId');
      if (response.data['success'] == true) {
        setState(() => _wards = response.data['data']);
      }
    } catch (_) {}
    finally {
      if (mounted) setState(() => _isLoadingGeo = false);
    }
  }

  Future<void> _loadPollingUnits(int wardId) async {
    setState(() {
      _isLoadingGeo = true;
      _pollingUnits = [];
      _selectedPuId = null;
    });
    try {
      final client = ref.read(apiClientProvider);
      final response =
          await client.get('/geography/polling-units?ward_id=$wardId');
      if (response.data['success'] == true) {
        setState(() => _pollingUnits = response.data['data']);
      }
    } catch (_) {}
    finally {
      if (mounted) setState(() => _isLoadingGeo = false);
    }
  }

  // ─── Submission ───────────────────────────────────────────────

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;

    if (_selectedLgaId == null || _selectedWardId == null) {
      _showSnack('Please select LGA and Ward', Colors.orange);
      return;
    }

    setState(() => _isSubmitting = true);

    try {
      final client = ref.read(apiClientProvider);

      final Map<String, dynamic> payload = {
        'name': _nameController.text.trim(),
        'phone': _phoneController.text.trim(),
      };

      if (_occupationController.text.trim().isNotEmpty) {
        payload['occupation'] = _occupationController.text.trim();
      }
      if (_selectedLgaId != null) {
        payload['lga_id'] = _selectedLgaId;
      }
      if (_selectedWardId != null) {
        payload['ward_id'] = _selectedWardId;
      }
      if (_selectedPuId != null) {
        payload['polling_unit_id'] = _selectedPuId;
      }
      if (_notesController.text.trim().isNotEmpty) {
        payload['notes'] = _notesController.text.trim();
      }


      if (_passportImage != null) {
        payload['passport'] = await dio.MultipartFile.fromFile(
          _passportImage!.path,
          filename: _passportImage!.path.split('/').last,
        );
      }

      final formData = dio.FormData.fromMap(payload);

      final response = await client.post('/members/recruit', data: formData);

      if (response.data['success'] == true) {
        setState(() => _showSuccess = true);
        _successAnimController.forward();
      } else {
        _showSnack(
          response.data['message'] ?? 'Recruitment failed.',
          Colors.red,
        );
      }
    } catch (e) {
      final errorMsg = _extractErrorMessage(e);
      _showSnack(errorMsg, Colors.red);
    } finally {
      if (mounted) setState(() => _isSubmitting = false);
    }
  }

  String _extractErrorMessage(dynamic error) {
    try {
      if (error.toString().contains('DioException')) {
        final response = (error as dynamic).response;
        if (response != null && response.data != null) {
          if (response.data['message'] != null) {
            return response.data['message'];
          }
          if (response.data['errors'] != null) {
            final errors = response.data['errors'] as Map<String, dynamic>;
            return errors.values
                .expand((v) => v is List ? v : [v])
                .join('\n');
          }
        }
      }
    } catch (_) {}
    return 'Connection error. Please try again.';
  }

  void _showSnack(String message, Color color) {
    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: color,
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
        margin: const EdgeInsets.all(16),
      ),
    );
  }

  void _resetForm() {
    _nameController.clear();
    _phoneController.clear();
    _occupationController.clear();
    _notesController.clear();
    setState(() {
      _selectedLgaId = null;
      _selectedWardId = null;
      _selectedPuId = null;
      _wards = [];
      _pollingUnits = [];
      _showSuccess = false;
      _passportImage = null;
    });
    _successAnimController.reset();
  }

  // ─── Build ────────────────────────────────────────────────────

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF0F4FF),
      appBar: AppBar(
        backgroundColor: Colors.white,
        elevation: 0,
        scrolledUnderElevation: 0,
        shape: const Border(
          bottom: BorderSide(color: Color(0xFFCED4E0), width: 0.5),
        ),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_rounded, color: Color(0xFF3451DB)),
          onPressed: () => Navigator.pop(context),
        ),
        title: const Row(
          children: [
            Icon(Icons.person_add_alt_1_rounded,
                color: Color(0xFF3451DB), size: 22),
            SizedBox(width: 8),
            Text(
              'Recruit Volunteer',
              style: TextStyle(
                fontFamily: 'Inter',
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: Color(0xFF3451DB),
              ),
            ),
          ],
        ),
      ),
      body: AnimatedSwitcher(
        duration: const Duration(milliseconds: 400),
        child: _showSuccess ? _buildSuccessView() : _buildFormView(),
      ),
    );
  }

  // ─── Success View ─────────────────────────────────────────────

  Widget _buildSuccessView() {
    return Center(
      key: const ValueKey('success'),
      child: FadeTransition(
        opacity: _fadeAnim,
        child: ScaleTransition(
          scale: _scaleAnim,
          child: Padding(
            padding: const EdgeInsets.all(32),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Container(
                  width: 100,
                  height: 100,
                  decoration: BoxDecoration(
                    color: const Color(0xFF3451DB).withOpacity(0.1),
                    shape: BoxShape.circle,
                  ),
                  child: const Icon(
                    Icons.check_circle_rounded,
                    size: 64,
                    color: Color(0xFF3451DB),
                  ),
                ),
                const SizedBox(height: 24),
                const Text(
                  'Volunteer Recruited!',
                  style: TextStyle(
                    fontFamily: 'Inter',
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                    color: Color(0xFF3451DB),
                  ),
                ),
                const SizedBox(height: 8),
                const Text(
                  'The new volunteer has been added to the campaign\nand 10 campaign points have been awarded to you.',
                  textAlign: TextAlign.center,
                  style: TextStyle(
                    fontFamily: 'Inter',
                    fontSize: 14,
                    color: Color(0xFF64748B),
                    height: 1.5,
                  ),
                ),
                const SizedBox(height: 12),
                Container(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  decoration: BoxDecoration(
                    color: const Color(0xFFFFF3E0),
                    borderRadius: BorderRadius.circular(999),
                    border: Border.all(
                        color: const Color(0xFFFF8C00).withOpacity(0.4)),
                  ),
                  child: const Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Icon(Icons.star_rounded,
                          color: Color(0xFFF57C00), size: 18),
                      SizedBox(width: 6),
                      Text(
                        '+10 Points',
                        style: TextStyle(
                          fontFamily: 'Inter',
                          fontSize: 14,
                          fontWeight: FontWeight.bold,
                          color: Color(0xFFF57C00),
                        ),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 32),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    OutlinedButton.icon(
                      onPressed: _resetForm,
                      icon: const Icon(Icons.person_add_alt_1_rounded,
                          size: 18),
                      label: const Text('Recruit Another'),
                      style: OutlinedButton.styleFrom(
                        foregroundColor: const Color(0xFF3451DB),
                        side: const BorderSide(color: Color(0xFF3451DB)),
                        padding: const EdgeInsets.symmetric(
                            horizontal: 20, vertical: 14),
                        shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12)),
                      ),
                    ),
                    const SizedBox(width: 12),
                    ElevatedButton.icon(
                      onPressed: () => Navigator.pop(context, true),
                      icon: const Icon(Icons.check_rounded, size: 18),
                      label: const Text('Done'),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF3451DB),
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(
                            horizontal: 24, vertical: 14),
                        shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12)),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  // ─── Form View ────────────────────────────────────────────────

  Widget _buildFormView() {
    final List<DropdownMenuItem<int>> lgaItems = [];
    final Set<int> uniqueLgaIds = {};
    for (var lga in _lgas) {
      if (lga['id'] != null) {
        final id = lga['id'] is int ? lga['id'] as int : int.tryParse(lga['id'].toString()) ?? 0;
        if (!uniqueLgaIds.contains(id)) {
          uniqueLgaIds.add(id);
          lgaItems.add(DropdownMenuItem<int>(
            value: id,
            child: Text(lga['name']?.toString() ?? ''),
          ));
        }
      }
    }

    final List<DropdownMenuItem<int>> wardItems = [];
    final Set<int> uniqueWardIds = {};
    for (var ward in _wards) {
      if (ward['id'] != null) {
        final id = ward['id'] is int ? ward['id'] as int : int.tryParse(ward['id'].toString()) ?? 0;
        if (!uniqueWardIds.contains(id)) {
          uniqueWardIds.add(id);
          wardItems.add(DropdownMenuItem<int>(
            value: id,
            child: Text(ward['name']?.toString() ?? ''),
          ));
        }
      }
    }

    final List<DropdownMenuItem<int>> puItems = [];
    final Set<int> uniquePuIds = {};
    for (var pu in _pollingUnits) {
      if (pu['id'] != null) {
        final id = pu['id'] is int ? pu['id'] as int : int.tryParse(pu['id'].toString()) ?? 0;
        if (!uniquePuIds.contains(id)) {
          uniquePuIds.add(id);
          puItems.add(DropdownMenuItem<int>(
            value: id,
            child: Text(
              '${pu['code'] ?? ''} — ${pu['name'] ?? ''}',
              overflow: TextOverflow.ellipsis,
            ),
          ));
        }
      }
    }

    return SingleChildScrollView(
      key: const ValueKey('form'),
      padding: const EdgeInsets.all(20),
      child: Form(
        key: _formKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // Header Info Card
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                gradient: const LinearGradient(
                  colors: [Color(0xFF4361EE), Color(0xFF1A237E)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: BorderRadius.circular(14),
              ),
              child: Row(
                children: [
                  Container(
                    width: 48,
                    height: 48,
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.15),
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: const Icon(Icons.group_add_rounded,
                        color: Colors.white, size: 26),
                  ),
                  const SizedBox(width: 14),
                  const Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          'Add New Volunteer',
                          style: TextStyle(
                            fontFamily: 'Inter',
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                            color: Colors.white,
                          ),
                        ),
                        SizedBox(height: 4),
                        Text(
                          'Fill in details to recruit a supporter into the Onyendozi Connect campaign.',
                          style: TextStyle(
                            fontFamily: 'Inter',
                            fontSize: 12,
                            color: Color(0xFFE8EDFF),
                            height: 1.4,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 24),

            // ── Section: Passport Photo ──
            Center(
              child: Stack(
                children: [
                  Container(
                    width: 100,
                    height: 100,
                    decoration: BoxDecoration(
                      color: Colors.white,
                      shape: BoxShape.circle,
                      border: Border.all(color: const Color(0xFFCED4E0), width: 1.5),
                      boxShadow: [
                        BoxShadow(
                          color: const Color(0xFF3451DB).withOpacity(0.06),
                          blurRadius: 10,
                          offset: const Offset(0, 4),
                        ),
                      ],
                    ),
                    child: ClipOval(
                      child: _passportImage != null
                          ? Image.file(
                              _passportImage!,
                              width: 100,
                              height: 100,
                              fit: BoxFit.cover,
                            )
                          : Container(
                              color: const Color(0xFFF0F4FF),
                              child: const Icon(
                                Icons.person_rounded,
                                size: 50,
                                color: Color(0xFF3451DB),
                              ),
                            ),
                    ),
                  ),
                  Positioned(
                    bottom: 0,
                    right: 0,
                    child: Material(
                      elevation: 3,
                      shadowColor: Colors.black.withOpacity(0.3),
                      shape: const CircleBorder(),
                      color: const Color(0xFF3451DB),
                      child: InkWell(
                        onTap: () => _showImageSourceOptions(context),
                        customBorder: const CircleBorder(),
                        child: Container(
                          padding: const EdgeInsets.all(8),
                          child: Icon(
                            _passportImage != null
                                ? Icons.edit_rounded
                                : Icons.add_a_photo_rounded,
                            size: 16,
                            color: Colors.white,
                          ),
                        ),
                      ),
                    ),
                  ),
                  if (_passportImage != null)
                    Positioned(
                      top: 0,
                      right: 0,
                      child: Material(
                        elevation: 3,
                        shadowColor: Colors.black.withOpacity(0.3),
                        shape: const CircleBorder(),
                        color: Colors.red,
                        child: InkWell(
                          onTap: _removeImage,
                          customBorder: const CircleBorder(),
                          child: Container(
                            padding: const EdgeInsets.all(6),
                            child: const Icon(
                              Icons.close_rounded,
                              size: 12,
                              color: Colors.white,
                            ),
                          ),
                        ),
                      ),
                    ),
                ],
              ),
            ),
            const SizedBox(height: 8),
            Center(
              child: Text(
                _passportImage != null ? 'Passport Photo Attached' : 'Attach Passport Photo (Optional)',
                style: const TextStyle(
                  fontFamily: 'Inter',
                  fontSize: 12,
                  fontWeight: FontWeight.bold,
                  color: Color(0xFF64748B),
                ),
              ),
            ),
            const SizedBox(height: 24),

            // ── Section: Personal Information ──
            _buildSectionHeader(
              icon: Icons.person_rounded,
              title: 'PERSONAL INFORMATION',
            ),
            const SizedBox(height: 12),
            _buildTextField(
              controller: _nameController,
              label: 'Full Name',
              icon: Icons.badge_outlined,
              hint: 'e.g. Chinedu Okoro',
              validator: (value) {
                if (value == null || value.trim().isEmpty) {
                  return 'Please enter the volunteer\'s full name';
                }
                if (value.trim().length < 3) {
                  return 'Name must be at least 3 characters';
                }
                return null;
              },
            ),
            const SizedBox(height: 14),
            _buildTextField(
              controller: _phoneController,
              label: 'Phone Number',
              icon: Icons.phone_outlined,
              hint: 'e.g. 08012345678',
              keyboardType: TextInputType.phone,
              validator: (value) {
                if (value == null || value.trim().isEmpty) {
                  return 'Please enter a phone number';
                }
                final digits = value.replaceAll(RegExp(r'\D'), '');
                if (digits.length < 10 || digits.length > 14) {
                  return 'Enter a valid phone number';
                }
                return null;
              },
            ),
            const SizedBox(height: 14),
            _buildTextField(
              controller: _occupationController,
              label: 'Occupation (optional)',
              icon: Icons.work_outline_rounded,
              hint: 'e.g. Teacher, Trader, Student',
              required: false,
            ),
            const SizedBox(height: 24),

            // ── Section: Geographic Assignment ──
            _buildSectionHeader(
              icon: Icons.location_on_rounded,
              title: 'GEOGRAPHIC ASSIGNMENT',
            ),
            const SizedBox(height: 12),
            _buildDropdown<int>(
              value: uniqueLgaIds.contains(_selectedLgaId) ? _selectedLgaId : null,
              label: 'Local Government Area (LGA)',
              icon: Icons.location_city_rounded,
              items: lgaItems,
              validator: (value) =>
                  value == null ? 'Please select an LGA' : null,
              onChanged: (val) {
                if (val != null) {
                  setState(() => _selectedLgaId = val);
                  _loadWards(val);
                }
              },
            ),
            const SizedBox(height: 14),
            _buildDropdown<int>(
              value: uniqueWardIds.contains(_selectedWardId) ? _selectedWardId : null,
              label: 'Ward',
              icon: Icons.map_rounded,
              items: wardItems,
              validator: (value) =>
                  value == null ? 'Please select a Ward' : null,
              onChanged: (val) {
                if (val != null) {
                  setState(() => _selectedWardId = val);
                  _loadPollingUnits(val);
                }
              },
            ),
            const SizedBox(height: 14),
            _buildDropdown<int>(
              value: uniquePuIds.contains(_selectedPuId) ? _selectedPuId : null,
              label: 'Polling Unit (optional)',
              icon: Icons.how_to_vote_rounded,
              items: puItems,
              onChanged: (val) {
                setState(() => _selectedPuId = val);
              },
            ),
            const SizedBox(height: 24),

            // ── Section: Additional Notes ──
            _buildSectionHeader(
              icon: Icons.notes_rounded,
              title: 'ADDITIONAL NOTES',
            ),
            const SizedBox(height: 12),
            _buildTextField(
              controller: _notesController,
              label: 'Notes (optional)',
              icon: Icons.edit_note_rounded,
              hint:
                  'How did you meet this volunteer? Any additional context...',
              maxLines: 3,
              required: false,
            ),
            const SizedBox(height: 28),

            // ── Info callout ──
            Container(
              padding: const EdgeInsets.all(14),
              decoration: BoxDecoration(
                color: const Color(0xFFFFF3E0),
                borderRadius: BorderRadius.circular(12),
                border: Border.all(
                    color: const Color(0xFFFF8C00).withOpacity(0.3)),
              ),
              child: const Row(
                children: [
                  Icon(Icons.info_outline_rounded,
                      color: Color(0xFFF57C00), size: 20),
                  SizedBox(width: 10),
                  Expanded(
                    child: Text(
                      'A temporary password will be generated automatically. The volunteer can reset it via SMS OTP.',
                      style: TextStyle(
                        fontFamily: 'Inter',
                        fontSize: 12,
                        color: Color(0xFFE65100),
                        height: 1.4,
                      ),
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),

            // ── Submit Button ──
            SizedBox(
              height: 56,
              child: ElevatedButton(
                onPressed: (_isSubmitting || _isLoadingGeo) ? null : _submit,
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF4361EE),
                  disabledBackgroundColor:
                      const Color(0xFF4361EE).withOpacity(0.5),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(14),
                  ),
                  elevation: 0,
                ),
                child: _isSubmitting
                    ? const SizedBox(
                        height: 22,
                        width: 22,
                        child: CircularProgressIndicator(
                          color: Colors.white,
                          strokeWidth: 2.5,
                        ),
                      )
                    : const Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.person_add_alt_1_rounded,
                              color: Colors.white, size: 20),
                          SizedBox(width: 8),
                          Text(
                            'Recruit Volunteer',
                            style: TextStyle(
                              fontFamily: 'Inter',
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: Colors.white,
                            ),
                          ),
                        ],
                      ),
              ),
            ),
            const SizedBox(height: 32),
          ],
        ),
      ),
    );
  }

  // ─── Reusable Widgets ─────────────────────────────────────────

  Widget _buildSectionHeader({
    required IconData icon,
    required String title,
  }) {
    return Row(
      children: [
        Container(
          width: 28,
          height: 28,
          decoration: BoxDecoration(
            color: const Color(0xFF3451DB).withOpacity(0.08),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Icon(icon, size: 16, color: const Color(0xFF3451DB)),
        ),
        const SizedBox(width: 10),
        Text(
          title,
          style: const TextStyle(
            fontFamily: 'Inter',
            fontSize: 12,
            fontWeight: FontWeight.bold,
            color: Color(0xFF64748B),
            letterSpacing: 0.8,
          ),
        ),
      ],
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String label,
    required IconData icon,
    String? hint,
    TextInputType? keyboardType,
    int maxLines = 1,
    bool required = true,
    String? Function(String?)? validator,
  }) {
    return TextFormField(
      controller: controller,
      keyboardType: keyboardType,
      maxLines: maxLines,
      style: const TextStyle(fontFamily: 'Inter', fontSize: 15),
      decoration: InputDecoration(
        labelText: label,
        hintText: hint,
        hintStyle: const TextStyle(
          fontFamily: 'Inter',
          fontSize: 13,
          color: Color(0xFFB0B8C9),
        ),
        labelStyle: const TextStyle(
          fontFamily: 'Inter',
          fontSize: 14,
          color: Color(0xFF94A3B8),
        ),
        prefixIcon: Icon(icon, color: const Color(0xFF94A3B8), size: 20),
        filled: true,
        fillColor: Colors.white,
        contentPadding:
            const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: Color(0xFFCED4E0), width: 1),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: Color(0xFFCED4E0), width: 1),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: Color(0xFF4361EE), width: 1.5),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: Colors.red, width: 1),
        ),
      ),
      validator: validator ??
          (required
              ? (value) {
                  if (value == null || value.trim().isEmpty) {
                    return 'This field is required';
                  }
                  return null;
                }
              : null),
    );
  }

  Widget _buildDropdown<T>({
    required T? value,
    required String label,
    required IconData icon,
    required List<DropdownMenuItem<T>> items,
    required void Function(T?) onChanged,
    String? Function(T?)? validator,
  }) {
    return DropdownButtonFormField<T>(
      value: value,
      isExpanded: true,
      style: const TextStyle(
          fontFamily: 'Inter', fontSize: 15, color: Color(0xFF1E293B)),
      decoration: InputDecoration(
        labelText: label,
        labelStyle: const TextStyle(
          fontFamily: 'Inter',
          fontSize: 14,
          color: Color(0xFF94A3B8),
        ),
        prefixIcon: Icon(icon, color: const Color(0xFF94A3B8), size: 20),
        filled: true,
        fillColor: Colors.white,
        contentPadding:
            const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: Color(0xFFCED4E0), width: 1),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: Color(0xFFCED4E0), width: 1),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: Color(0xFF4361EE), width: 1.5),
        ),
      ),
      items: items,
      validator: validator,
      onChanged: onChanged,
    );
  }
}
